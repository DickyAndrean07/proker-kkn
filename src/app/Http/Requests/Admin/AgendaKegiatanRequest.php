<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AgendaKegiatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isAdminDusun();
    }

    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi_singkat' => ['required', 'string'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'jam' => ['nullable', 'date_format:H:i'],
            'lokasi_text' => ['required', 'string', 'max:255'],
            'manual_status_override' => ['nullable', Rule::in(['AKAN_DATANG', 'BERLANGSUNG', 'SELESAI'])],
            'media' => ['nullable', 'array'],
            'media.*.file' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:3072'],
            'media.*.role' => ['required_with:media.*.file', Rule::in(['POSTER_AWAL', 'DOKUMENTASI'])],
            'existing_media_remove' => ['nullable', 'array'],
            'existing_media_remove.*' => ['integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'judul.required' => 'Judul agenda wajib diisi.',
            'deskripsi_singkat.required' => 'Deskripsi singkat wajib diisi.',
            'tanggal_mulai.required' => 'Tanggal mulai kegiatan wajib diisi.',
            'tanggal_mulai.date' => 'Format tanggal mulai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
            'jam.date_format' => 'Format waktu kegiatan harus berupa JJ:MM (misal 09:00).',
            'lokasi_text.required' => 'Lokasi kegiatan wajib diisi.',
            'manual_status_override.in' => 'Status override harus salah satu dari: AKAN_DATANG, BERLANGSUNG, atau SELESAI.',
            'media.*.file.image' => 'Berkas media harus berupa gambar.',
            'media.*.file.mimes' => 'Format media harus JPG, PNG, atau WebP.',
            'media.*.file.max' => 'Ukuran berkas media maksimal 3MB.',
            'media.*.role.in' => 'Peran media harus POSTER_AWAL atau DOKUMENTASI.',
        ];
    }
}
