<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class MediaService
{
    private const ALLOWED_MIMES = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    private string $disk;

    public function __construct()
    {
        $this->disk = config('filesystems.media_disk', 'public');
    }

    /**
     * Store and process an uploaded image.
     * Resizes if dimensions exceed maxWidth/maxHeight, converts to WebP if supported,
     * and saves to storage disk returning a storage-relative path.
     */
    public function storeImage(
        UploadedFile $file,
        string $directory,
        int $maxWidth = 1600,
        int $maxHeight = 1600,
        int $quality = 82
    ): string {
        $realPath = $file->getRealPath();
        if (! $realPath || ! file_exists($realPath)) {
            throw new InvalidArgumentException('Berkas upload tidak valid.');
        }

        // Validate MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $realPath);
        finfo_close($finfo);

        if (! array_key_exists($mimeType, self::ALLOWED_MIMES)) {
            throw new InvalidArgumentException('Format gambar tidak didukung. Gunakan JPG, PNG, atau WebP.');
        }

        // Validate image dimensions / validity
        $imageInfo = @getimagesize($realPath);
        if ($imageInfo === false) {
            throw new InvalidArgumentException('Berkas gambar rusak atau tidak valid.');
        }

        [$origWidth, $origHeight] = $imageInfo;

        // Load image resource via GD
        $srcImage = match ($mimeType) {
            'image/jpeg' => @imagecreatefromjpeg($realPath),
            'image/png' => @imagecreatefrompng($realPath),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($realPath) : null,
            default => null,
        };

        if (! $srcImage) {
            throw new RuntimeException('Gagal memproses berkas gambar.');
        }

        // Calculate target dimensions
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $targetWidth = (int) round($origWidth * $ratio);
            $targetHeight = (int) round($origHeight * $ratio);
        }

        // Create canvas
        $dstImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve alpha transparency for PNG/WebP
        imagealphablending($dstImage, false);
        imagesavealpha($dstImage, true);

        imagecopyresampled(
            $dstImage,
            $srcImage,
            0, 0, 0, 0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        // Export to temp file
        $tempFile = tempnam(sys_get_temp_dir(), 'img_');
        $outputExt = 'webp';

        if (function_exists('imagewebp')) {
            imagewebp($dstImage, $tempFile, $quality);
        } else {
            imagejpeg($dstImage, $tempFile, $quality);
            $outputExt = 'jpg';
        }

        imagedestroy($srcImage);
        imagedestroy($dstImage);

        // Save to Laravel disk
        $filename = Str::random(40).'.'.$outputExt;
        $cleanDirectory = trim(str_replace(['..', '\\'], ['', '/'], $directory), '/');
        $relativePath = $cleanDirectory.'/'.$filename;

        $stream = fopen($tempFile, 'r');
        Storage::disk($this->disk)->put($relativePath, $stream);
        if (is_resource($stream)) {
            fclose($stream);
        }
        @unlink($tempFile);

        return $relativePath;
    }

    /**
     * Safely delete an existing media file from the storage disk.
     */
    public function deleteImage(?string $relativePath): void
    {
        if (! $relativePath) {
            return;
        }

        $cleanPath = trim(str_replace(['..', '\\'], ['', '/'], $relativePath), '/');

        if (Storage::disk($this->disk)->exists($cleanPath)) {
            Storage::disk($this->disk)->delete($cleanPath);
        }
    }
}
