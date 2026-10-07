<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoordinateResolverController extends Controller
{
    /**
     * Resolve latitude and longitude from raw text or Google Maps URLs (including maps.app.goo.gl shortlinks).
     */
    public function resolve(Request $request): JsonResponse
    {
        $request->validate([
            'url' => ['required', 'string', 'max:2000'],
        ]);

        $raw = trim($request->input('url'));

        try {
            $coords = $this->extractCoordinates($raw);

            if ($coords) {
                return response()->json([
                    'success' => true,
                    'lat' => $coords['lat'],
                    'lng' => $coords['lng'],
                    'type' => $coords['type'],
                    'place' => $coords['place'] ?? null,
                    'message' => 'Titik koordinat berhasil dikenali.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Koordinat tidak ditemukan pada tautan atau teks tersebut. Pastikan tautan berasal dari Google Maps atau berformat desimal/DMS.',
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengurai tautan: ' . $e->getMessage(),
            ], 422);
        }
    }

    private function extractCoordinates(string $text): ?array
    {
        // 1. Check direct DMS format: 7°27'28.9"S 112°25'48.7"E
        $dmsRegex = '/(\d+(?:\.\d+)?)\s*°\s*(\d+(?:\.\d+)?)\s*[\'′]?\s*(\d+(?:\.\d+)?)\s*["″]?\s*([NSEWnsew])/i';
        if (preg_match_all($dmsRegex, $text, $dmsMatches, PREG_SET_ORDER) && count($dmsMatches) >= 2) {
            $lat = null;
            $lng = null;
            foreach ($dmsMatches as $m) {
                $deg = (float) $m[1];
                $min = (float) $m[2];
                $sec = (float) $m[3];
                $dir = strtoupper($m[4]);
                $dec = $deg + ($min / 60) + ($sec / 3600);
                if (in_array($dir, ['S', 'W'])) {
                    $dec = -$dec;
                }
                if (in_array($dir, ['N', 'S'])) {
                    $lat = $dec;
                }
                if (in_array($dir, ['E', 'W'])) {
                    $lng = $dec;
                }
            }
            if ($lat !== null && $lng !== null) {
                return ['lat' => $lat, 'lng' => $lng, 'type' => 'DMS (Derajat)'];
            }
        }

        // 2. Direct decimal in URL or raw text: @-7.123,110.123 or ?q=-7.123,110.123
        if (preg_match('/(?:@|[?&](?:q|destination|ll|center)=|\/dir\/\/)(-?\d{1,2}\.\d+)[,\s]+(-?\d{1,3}\.\d+)/i', $text, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2], 'type' => 'Google Maps Link'];
        }

        // Protobuf pattern directly in text: !3d-7.123!4d112.123
        if (preg_match('/!3d(-?\d{1,2}\.\d+)!4d(-?\d{1,3}\.\d+)/i', $text, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2], 'type' => 'Google Maps Link'];
        }

        // 3. Simple Decimal numbers: -7.123456, 110.123456
        if (preg_match('#^(-?\d{1,2}\.\d+)[,\s/]+(-?\d{1,3}\.\d+)$#', $text, $m)) {
            return ['lat' => (float) $m[1], 'lng' => (float) $m[2], 'type' => 'Koordinat Desimal'];
        }

        // 4. If it's a URL, resolve shortlink (maps.app.goo.gl, goo.gl, etc.)
        if (filter_var($text, FILTER_VALIDATE_URL)) {
            $host = parse_url($text, PHP_URL_HOST);
            if ($host && preg_match('/(?:google\.com|goo\.gl|maps\.app\.goo\.gl)$/i', $host)) {
                $ch = curl_init($text);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
                curl_setopt($ch, CURLOPT_TIMEOUT, 8);
                $response = curl_exec($ch);
                $finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL) ?: '';
                curl_close($ch);

                $pool = $finalUrl . "\n" . ($response ?: '');

                $place = null;
                if (preg_match('#/maps/place/([^/?]+)#i', $pool, $pm)) {
                    $rawPlace = urldecode($pm[1]);
                    // remove plus codes like GCRJ+Q3G
                    $rawPlace = preg_replace('/^[A-Z0-9]{4,8}\+[A-Z0-9]{2,4}\s*/', '', $rawPlace);
                    $place = trim($rawPlace);
                }

                // Check protobuf coords
                if (preg_match('/!3d(-?\d{1,2}\.\d+)!4d(-?\d{1,3}\.\d+)/i', $pool, $m)) {
                    return [
                        'lat' => (float) $m[1],
                        'lng' => (float) $m[2],
                        'type' => 'Google Maps Sharelink HP',
                        'place' => $place,
                    ];
                }

                // Check query coords: ?q=-7.123,112.123 or @-7.123,112.123
                if (preg_match('/(?:@|[?&](?:q|destination|ll|center)=|\/dir\/\/)(-?\d{1,2}\.\d+)[,\s]+(-?\d{1,3}\.\d+)/i', $pool, $m)) {
                    return [
                        'lat' => (float) $m[1],
                        'lng' => (float) $m[2],
                        'type' => 'Google Maps Link',
                        'place' => $place,
                    ];
                }
            }
        }

        return null;
    }
}
