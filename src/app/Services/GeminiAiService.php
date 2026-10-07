<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiAiService
{
    private bool $enabled;

    private string $provider;

    private ?string $baseUrl;

    private string $model;

    private ?string $apiKey;

    private int $timeout;

    private int $maxTokens;

    /**
     * @var array<string> Ordered list of fallback models for auto-failover on rate limits
     */
    private array $fallbackModels;

    /**
     * @var array<string, mixed> Metadata of the last generation (model used, fallback flag, latency)
     */
    protected array $lastGenerationMeta = [];

    public function __construct()
    {
        $this->enabled = (bool) config('ai.enabled', true);
        $this->provider = strtolower((string) config('ai.provider', 'gemini'));
        $this->baseUrl = config('ai.base_url') ? trim((string) config('ai.base_url')) : null;
        $rawModel = (string) config('ai.model', 'gemini-3.5-flash-lite');
        $this->model = $this->provider === 'gemini' ? $this->normalizeModelName($rawModel) : $rawModel;
        $this->apiKey = trim((string) config('ai.api_key'), " \t\n\r\0\x0B\"'");
        $this->timeout = (int) config('ai.timeout_seconds', 20);
        $this->maxTokens = (int) config('ai.max_output_tokens', 2048);

        $rawFallbacks = config('ai.fallback_models');
        if (is_array($rawFallbacks) && ! empty($rawFallbacks)) {
            $this->fallbackModels = array_values($rawFallbacks);
        } else {
            // Smart defaults based on provider and primary model
            if ($this->provider === 'groq') {
                $this->fallbackModels = match ($this->model) {
                    'openai/gpt-oss-120b' => ['groq/compound-mini', 'openai/gpt-oss-20b'],
                    'groq/compound-mini' => ['openai/gpt-oss-120b', 'openai/gpt-oss-20b'],
                    default => ['groq/compound-mini', 'openai/gpt-oss-20b'],
                };
            } elseif ($this->provider === 'gemini') {
                $this->fallbackModels = ['gemini-2.5-flash', 'gemini-flash-latest'];
            } else {
                $this->fallbackModels = [];
            }
        }
    }

    private function normalizeModelName(string $rawModel): string
    {
        $cleaned = trim(strtolower($rawModel), " \t\n\r\0\x0B\"'");
        $cleaned = preg_replace('/\s+/', '-', $cleaned);

        if (str_contains($cleaned, 'flash-lite') || str_contains($cleaned, 'flash_lite') || str_contains($cleaned, 'lite')) {
            return 'gemini-3.5-flash-lite';
        }

        if ($cleaned === 'gemini-3.6-flash' || $cleaned === 'gemini-3.6' || $cleaned === 'gemini-3-6-flash') {
            return 'gemini-3.6-flash';
        }

        if ($cleaned === 'gemini-3.5-flash' || $cleaned === 'gemini-3.5') {
            return 'gemini-3.5-flash';
        }

        if ($cleaned === 'gemini-flash-latest' || $cleaned === 'flash-latest') {
            return 'gemini-flash-latest';
        }

        if (str_starts_with($cleaned, 'gemini-2') || str_starts_with($cleaned, 'gemini-1')) {
            return 'gemini-3.5-flash-lite';
        }

        return $cleaned ?: 'gemini-3.5-flash-lite';
    }

    /**
     * Check if AI service is configured and ready.
     */
    public function isAvailable(): bool
    {
        return $this->enabled && ! empty($this->apiKey);
    }

    /**
     * Generate structured draft or text improvement.
     *
     * @param  string  $feature  (pengumuman_draft|agenda_draft|umkm_draft|improve_text)
     * @param  string  $mode  (draft|rapikan|persingkat|formal)
     * @param  string|null  $notes  Factual raw notes supplied by admin
     * @param  string|null  $existingText  Existing text if improving
     * @param  array|null  $structuredInput  Structured 5W1H or UMKM parameters
     * @param  string  $draftLength  (ringkas|standar|lengkap)
     *
     * @throws Exception
     */
    public function generate(
        string $feature,
        string $mode,
        ?string $notes = null,
        ?string $existingText = null,
        ?array $structuredInput = null,
        string $draftLength = 'standar'
    ): array {
        if (! $this->enabled) {
            throw new Exception('Layanan asisten AI sedang dinonaktifkan oleh administrator.');
        }

        if (empty($this->apiKey)) {
            throw new Exception('Kunci API asisten AI belum dikonfigurasi pada server.');
        }

        $systemInstruction = $this->buildSystemInstruction($feature, $mode);
        $userPrompt = $this->buildUserPrompt($feature, $mode, $notes, $existingText, $structuredInput, $draftLength);

        $modelsToTry = array_values(array_unique(array_merge([$this->model], $this->fallbackModels)));
        $totalModels = count($modelsToTry);
        $attempt = 0;
        $lastException = null;

        foreach ($modelsToTry as $currentModel) {
            $attempt++;

            if ($this->provider === 'gemini') {
                $endpoint = "https://generativelanguage.googleapis.com/v1beta/models/{$currentModel}:generateContent?key={$this->apiKey}";
                $payload = [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $userPrompt],
                            ],
                        ],
                    ],
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $systemInstruction],
                        ],
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0.3,
                        'maxOutputTokens' => $this->maxTokens,
                    ],
                ];
                $headers = [
                    'x-goog-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ];
            } else {
                // OpenAI-Compatible Providers (Groq, OpenRouter, etc.)
                $endpoint = $this->resolveOpenAiEndpoint();
                $payload = [
                    'model' => $currentModel,
                    'messages' => [
                        ['role' => 'system', 'content' => $systemInstruction],
                        ['role' => 'user', 'content' => $userPrompt],
                    ],
                    'temperature' => 0.3,
                    'max_tokens' => $this->maxTokens,
                ];
                $headers = [
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'HTTP-Referer' => 'https://bendung.online',
                    'X-Title' => 'Portal Informasi Desa Bendung',
                    'Content-Type' => 'application/json',
                ];
            }

            $startTime = microtime(true);

            try {
                $response = Http::timeout($this->timeout)
                    ->withHeaders($headers)
                    ->post($endpoint, $payload);
                $latencyMs = round((microtime(true) - $startTime) * 1000);

                if (! $response->successful()) {
                    $status = $response->status();
                    $googleError = $response->json('error.message') ?? (string) $response->body();

                    Log::warning('[AI_ASSISTANT] Model request failed', [
                        'provider' => $this->provider,
                        'model' => $currentModel,
                        'attempt' => $attempt,
                        'status' => $status,
                        'feature' => $feature,
                        'error' => $googleError,
                        'latency_ms' => $latencyMs,
                    ]);

                    // If rate limited (429) or transient server error (500, 502, 503) and fallback models available:
                    if (in_array($status, [429, 500, 502, 503]) && $attempt < $totalModels) {
                        $nextModel = $modelsToTry[$attempt];
                        Log::info("[AI_ASSISTANT] Auto-failover: Model '{$currentModel}' encountered HTTP {$status}, seamlessly switching to fallback model '{$nextModel}'");
                        continue;
                    }

                    if ($status === 429) {
                        throw new Exception('Batas kuota layanan AI tercapai. Silakan coba beberapa saat lagi.');
                    }

                    if ($status === 400 && str_contains(strtolower($googleError), 'api key')) {
                        throw new Exception('Kunci API AI tidak valid. Pastikan API Key di file .env sudah benar.');
                    }

                    if ($status === 404) {
                        if ($attempt < $totalModels) {
                            $nextModel = $modelsToTry[$attempt];
                            Log::info("[AI_ASSISTANT] Auto-failover: Model '{$currentModel}' 404 not found, switching to fallback model '{$nextModel}'");
                            continue;
                        }
                        throw new Exception("Model AI '{$currentModel}' tidak ditemukan pada provider. Pastikan AI_MODEL di file .env sudah sesuai.");
                    }

                    if ($status === 403) {
                        throw new Exception('Akses API AI ditolak (403). Pastikan akun provider AI Anda aktif.');
                    }

                    $previewError = mb_strimwidth($googleError, 0, 160, '...');
                    throw new Exception("Gagal menghubungi penyedia AI ({$status}): {$previewError}");
                }

                $responseData = $response->json();

                if ($this->provider === 'gemini') {
                    $rawText = $responseData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                } else {
                    $rawText = $responseData['choices'][0]['message']['content'] ?? null;
                }

                if (empty($rawText)) {
                    if ($attempt < $totalModels) {
                        $nextModel = $modelsToTry[$attempt];
                        Log::info("[AI_ASSISTANT] Empty response from '{$currentModel}', switching to '{$nextModel}'");
                        continue;
                    }
                    throw new Exception('Penyedia AI tidak mengembalikan konten yang valid.');
                }

                $parsedData = $this->parseResponseContent($rawText, $feature);

                $latencySeconds = max(0.1, round($latencyMs / 1000, 1));
                $this->lastGenerationMeta = [
                    'model' => $currentModel,
                    'model_label' => $this->formatModelLabel($currentModel),
                    'is_fallback' => ($attempt > 1),
                    'attempt' => $attempt,
                    'latency_ms' => $latencyMs,
                    'latency_seconds' => $latencySeconds,
                ];

                Log::info('[AI_ASSISTANT] Draft generated successfully', [
                    'feature' => $feature,
                    'mode' => $mode,
                    'model_used' => $currentModel,
                    'attempt' => $attempt,
                    'latency_ms' => $latencyMs,
                ]);

                return $parsedData;
            } catch (Exception $e) {
                // If retryable and more models in chain, continue
                if ($attempt < $totalModels && (str_contains($e->getMessage(), 'Batas kuota') || str_contains($e->getMessage(), '429') || str_contains($e->getMessage(), 'timeout'))) {
                    $nextModel = $modelsToTry[$attempt];
                    Log::info("[AI_ASSISTANT] Auto-failover exception on '{$currentModel}', switching to '{$nextModel}': ".$e->getMessage());
                    continue;
                }

                $lastException = $e;
                Log::error('[AI_ASSISTANT] Exception occurred', [
                    'feature' => $feature,
                    'model' => $currentModel,
                    'attempt' => $attempt,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        throw ($lastException ?: new Exception('Gagal menghasilkan draf AI. Silakan coba beberapa saat lagi.'));
    }

    /**
     * Build strict system instructions forbidding fact invention and hallucination.
     */
    private function buildSystemInstruction(string $feature, string $mode): string
    {
        return <<<'INSTRUCTION'
Anda adalah asisten penulisan portal informasi desa resmi untuk Desa Bendung, Kecamatan Jetis, Kabupaten Mojokerto.
Tugas Anda HANYA menyusun, merapikan, atau memformat draf teks berdasarkan catatan fakta mentah yang diberikan oleh admin desa.

ATURAN KESELAMATAN & KEBIJAKAN KETAT:
1. HANYA gunakan fakta, nama, dan data yang secara eksplisit diberikan oleh admin.
2. JANGAN PERNAH mengarang tanggal, jam, durasi, lokasi gedung, nomor telepon, nama orang/pejabat, harga, atau klaim yang tidak tercantum dalam catatan admin.
3. Gunakan Bahasa Indonesia yang baku, sopan, komunikatif, dan sesuai etika pengumuman/informasi publik desa.
4. Jika catatan admin kurang lengkap, susun kerangka netral dan gunakan placeholder seperti "[Sebutkan rincian jika ada]" tanpa membuat detail fiktif.
5. Anda adalah asisten DRAFTING saja. Output WAJIB HANYA berupa JSON valid sesuai skema yang diminta, tanpa awalan atau akhiran markdown penjelasan.
INSTRUCTION;
    }

    /**
     * Build user prompt with expected JSON structure.
     */
    private function buildUserPrompt(
        string $feature,
        string $mode,
        ?string $notes,
        ?string $existingText,
        ?array $structuredInput = null,
        string $draftLength = 'standar'
    ): string {
        $prompt = "Mode Penulisan: {$mode}\n";

        if ($draftLength === 'ringkas') {
            $prompt .= "Gaya Panjang Draf: Ringkas, padat, dan langsung ke inti informasi pokok.\n\n";
        } elseif ($draftLength === 'lengkap') {
            $prompt .= "Gaya Panjang Draf: Lengkap, terperinci, dan jelas dengan susunan paragraf atau poin yang informatif.\n\n";
        }

        if (! empty($structuredInput) && is_array($structuredInput)) {
            if ($feature === 'pengumuman_draft' || $feature === 'agenda_draft') {
                $prompt .= "Rincian Fakta Terstruktur (Format 5W+1H):\n";
                if (! empty($structuredInput['who'])) {
                    $prompt .= "- WHO (Sasaran/Penyelenggara): {$structuredInput['who']}\n";
                }
                if (! empty($structuredInput['what'])) {
                    $prompt .= "- WHAT (Perihal/Nama Kegiatan): {$structuredInput['what']}\n";
                }
                if (! empty($structuredInput['when'])) {
                    $prompt .= "- WHEN (Hari, Tanggal & Jam): {$structuredInput['when']}\n";
                }
                if (! empty($structuredInput['where'])) {
                    $prompt .= "- WHERE (Tempat/Lokasi Gedung): {$structuredInput['where']}\n";
                }
                if (! empty($structuredInput['why'])) {
                    $prompt .= "- WHY (Tujuan/Latar Belakang): {$structuredInput['why']}\n";
                }
                if (! empty($structuredInput['how'])) {
                    $prompt .= "- HOW (Instruksi/Syarat/Perlengkapan): {$structuredInput['how']}\n";
                }
                $prompt .= "\n";
            } elseif ($feature === 'umkm_draft') {
                $prompt .= "Rincian Profil & Promosi Usaha UMKM:\n";
                if (! empty($structuredInput['business_name'])) {
                    $prompt .= "- Nama Usaha / Pemilik: {$structuredInput['business_name']}\n";
                }
                if (! empty($structuredInput['product_service'])) {
                    $prompt .= "- Produk / Layanan Unggulan: {$structuredInput['product_service']}\n";
                }
                if (! empty($structuredInput['usp_advantage'])) {
                    $prompt .= "- Keunggulan & Ciri Khas Produk: {$structuredInput['usp_advantage']}\n";
                }
                if (! empty($structuredInput['location'])) {
                    $prompt .= "- Lokasi / Dusun Produksi: {$structuredInput['location']}\n";
                }
                if (! empty($structuredInput['ordering_info'])) {
                    $prompt .= "- Cara Pesan / Kontak / Rentang Harga: {$structuredInput['ordering_info']}\n";
                }
            } elseif ($feature === 'desa_draft') {
                $prompt .= "Rincian Profil Selayang Pandang Desa:\n";
                if (! empty($structuredInput['entity_name'])) {
                    $prompt .= "- Nama Desa: {$structuredInput['entity_name']}\n";
                }
                if (! empty($structuredInput['geographic'])) {
                    $prompt .= "- Karakteristik Geografis / Alam: {$structuredInput['geographic']}\n";
                }
                if (! empty($structuredInput['livelihood'])) {
                    $prompt .= "- Potensi Unggulan & Mata Pencaharian: {$structuredInput['livelihood']}\n";
                }
                if (! empty($structuredInput['culture'])) {
                    $prompt .= "- Suasana Sosial & Tradisi Budaya: {$structuredInput['culture']}\n";
                }
                if (! empty($structuredInput['vision'])) {
                    $prompt .= "- Ciri Khas / Harapan Desa: {$structuredInput['vision']}\n";
                }
                $prompt .= "\n";
            } elseif ($feature === 'dusun_draft') {
                $prompt .= "Rincian Profil / Selayang Pandang Dusun:\n";
                if (! empty($structuredInput['entity_name'])) {
                    $prompt .= "- Nama Dusun: {$structuredInput['entity_name']}\n";
                }
                if (! empty($structuredInput['geographic'])) {
                    $prompt .= "- Wilayah & Letak Geografis: {$structuredInput['geographic']}\n";
                }
                if (! empty($structuredInput['livelihood'])) {
                    $prompt .= "- Potensi Wilayah & Kegiatan Ekonomi: {$structuredInput['livelihood']}\n";
                }
                if (! empty($structuredInput['culture'])) {
                    $prompt .= "- Kehidupan Warga & Kerukunan: {$structuredInput['culture']}\n";
                }
                if (! empty($structuredInput['vision'])) {
                    $prompt .= "- Keunikan & Karakter Dusun: {$structuredInput['vision']}\n";
                }
                $prompt .= "\n";
            } elseif ($feature === 'fasilitas_draft') {
                $prompt .= "Rincian Profil & Layanan Fasilitas Umum Desa:\n";
                if (! empty($structuredInput['facility_name'])) {
                    $prompt .= "- Nama Fasilitas: {$structuredInput['facility_name']}\n";
                }
                if (! empty($structuredInput['facility_category'])) {
                    $prompt .= "- Kategori Fasilitas: {$structuredInput['facility_category']}\n";
                }
                if (! empty($structuredInput['main_function'])) {
                    $prompt .= "- Fungsi Utama & Layanan Publik: {$structuredInput['main_function']}\n";
                }
                if (! empty($structuredInput['operational_hours'])) {
                    $prompt .= "- Jam Layanan / Waktu Buka: {$structuredInput['operational_hours']}\n";
                }
                if (! empty($structuredInput['amenities_capacity'])) {
                    $prompt .= "- Sarana Pendukung & Daya Tampung: {$structuredInput['amenities_capacity']}\n";
                }
                if (! empty($structuredInput['access_rules'])) {
                    $prompt .= "- Ketentuan Akses / Syarat Warga: {$structuredInput['access_rules']}\n";
                }
                $prompt .= "\n";
            }
        }

        if ($notes) {
            $prompt .= "Catatan Tambahan Admin:\n{$notes}\n\n";
        }

        if ($existingText) {
            $prompt .= "Teks Saat Ini Yang Ingin Diperbaiki:\n{$existingText}\n\n";
        }

        switch ($feature) {
            case 'pengumuman_draft':
                $prompt .= 'Susun draf pengumuman resmi desa. Kembalikan HANYA JSON: {"judul": "Judul resmi yang jelas", "isi": "Isi lengkap pengumuman resmi..."}';
                break;

            case 'agenda_draft':
                $prompt .= 'Susun draf rincian agenda/kegiatan desa. Kembalikan HANYA JSON: {"judul": "Nama kegiatan", "deskripsi": "Deskripsi susunan dan rincian kegiatan..."}';
                break;

            case 'umkm_draft':
                $prompt .= 'Susun draf deskripsi profil UMKM/usaha warga desa yang menarik dan informatif. Kembalikan HANYA JSON: {"deskripsi": "Paragraf deskripsi UMKM..."}';
                break;

            case 'desa_draft':
                $prompt .= 'Susun draf deskripsi / selayang pandang resmi desa yang berbobot, inspiratif, dan santun untuk portal publik desa. Kembalikan HANYA JSON: {"deskripsi": "Paragraf selayang pandang deskripsi desa..."}';
                break;

            case 'dusun_draft':
                $prompt .= 'Susun draf deskripsi singkat profil dusun yang menarik, hangat, dan informatif untuk portal publik dusun. Kembalikan HANYA JSON: {"deskripsi": "Paragraf profil ringkas dusun..."}';
                break;

            case 'fasilitas_draft':
                $prompt .= 'Susun draf deskripsi fasilitas umum desa yang informatif, jelas, dan komunikatif untuk warga desa. Kembalikan HANYA JSON: {"deskripsi": "Paragraf deskripsi fasilitas desa..."}';
                break;

            case 'improve_text':
            default:
                if ($mode === 'persingkat') {
                    $prompt .= 'Persingkat teks di atas tanpa membuang informasi faktual penting. Kembalikan HANYA JSON: {"teks_hasil": "Teks ringkas..."}';
                } elseif ($mode === 'formal') {
                    $prompt .= 'Ubah teks di atas menjadi gaya bahasa resmi/formal instansi desa yang sopan. Kembalikan HANYA JSON: {"teks_hasil": "Teks formal..."}';
                } else {
                    $prompt .= 'Rapikan ejaan, tanda baca, dan susunan kalimat teks di atas tanpa mengubah fakta. Kembalikan HANYA JSON: {"teks_hasil": "Teks rapi..."}';
                }
                break;
        }

        return $prompt;
    }

    /**
     * Resolve endpoint URL for OpenAI-compatible providers.
     */
    private function resolveOpenAiEndpoint(): string
    {
        if (! empty($this->baseUrl)) {
            $base = rtrim($this->baseUrl, '/');

            return str_ends_with($base, '/chat/completions') ? $base : "{$base}/chat/completions";
        }

        return match ($this->provider) {
            'openrouter' => 'https://openrouter.ai/api/v1/chat/completions',
            'groq' => 'https://api.groq.com/openai/v1/chat/completions',
            '9router' => 'http://localhost:20128/v1/chat/completions',
            default => 'https://openrouter.ai/api/v1/chat/completions',
        };
    }

    /**
     * Parse structured JSON from raw model text, stripping code fences or gracefully falling back.
     */
    private function parseResponseContent(string $rawText, string $feature): array
    {
        $cleanText = trim($rawText);

        // 1. Strip markdown code fences if present (e.g. ```json ... ``` or ``` ...)
        if (preg_match('/^```(?:json)?\s*(.*?)\s*```$/s', $cleanText, $matches)) {
            $cleanText = trim($matches[1]);
        }

        // 2. Try direct json_decode
        $decoded = json_decode($cleanText, true);
        if (is_array($decoded) && ! empty($decoded)) {
            return $this->formatParsedOutput($decoded, $feature, $cleanText);
        }

        // 3. Extract JSON object substring if model added surrounding text
        if (preg_match('/\{[\s\S]*\}/', $cleanText, $jsonMatch)) {
            $decoded = json_decode($jsonMatch[0], true);
            if (is_array($decoded) && ! empty($decoded)) {
                return $this->formatParsedOutput($decoded, $feature, $cleanText);
            }
        }

        // 4. Regex extraction for specific keys (handles incomplete or partially cut-off JSON)
        $extractedJudul = null;
        $extractedIsi = null;

        if (preg_match('/"judul"\s*:\s*"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s', $cleanText, $mJudul)) {
            $extractedJudul = stripslashes($mJudul[1]);
        }

        if (preg_match('/"(?:isi|deskripsi|teks_hasil)"\s*:\s*"([^"\\\\]*(?:\\\\.[^"\\\\]*)*)"/s', $cleanText, $mText)) {
            $extractedIsi = stripslashes($mText[1]);
        } elseif (preg_match('/"(?:isi|deskripsi|teks_hasil)"\s*:\s*"([^"]*)$/s', $cleanText, $mTextCut)) {
            $extractedIsi = stripslashes($mTextCut[1]);
        }

        if ($extractedJudul || $extractedIsi) {
            return match ($feature) {
                'pengumuman_draft' => [
                    'judul' => $extractedJudul ?: 'Pengumuman Resmi',
                    'isi' => $extractedIsi ?: '',
                ],
                'agenda_draft' => [
                    'judul' => $extractedJudul ?: 'Agenda Kegiatan',
                    'deskripsi' => $extractedIsi ?: '',
                ],
                'umkm_draft', 'desa_draft', 'dusun_draft', 'fasilitas_draft' => [
                    'deskripsi' => $extractedIsi ?: ($extractedJudul ?: $cleanText),
                ],
                default => [
                    'teks_hasil' => $extractedIsi ?: ($extractedJudul ?: $cleanText),
                ],
            };
        }

        // 5. Graceful Fallback if model returned plain text instead of JSON
        $lines = array_values(array_filter(array_map('trim', explode("\n", $cleanText))));
        $firstLine = $lines[0] ?? 'Draf Otomatis';
        $remainingLines = implode("\n", array_slice($lines, 1));
        $fallbackBody = ! empty($remainingLines) ? $remainingLines : $cleanText;

        return match ($feature) {
            'pengumuman_draft' => [
                'judul' => ltrim($firstLine, '#* -'),
                'isi' => $fallbackBody,
            ],
            'agenda_draft' => [
                'judul' => ltrim($firstLine, '#* -'),
                'deskripsi' => $fallbackBody,
            ],
            'umkm_draft', 'desa_draft', 'dusun_draft', 'fasilitas_draft' => [
                'deskripsi' => $cleanText,
            ],
            default => [
                'teks_hasil' => $cleanText,
            ],
        };
    }

    /**
     * Map decoded array keys to expected domain attributes.
     */
    private function formatParsedOutput(array $data, string $feature, string $fallback): array
    {
        return match ($feature) {
            'pengumuman_draft' => [
                'judul' => (string) ($data['judul'] ?? $data['title'] ?? 'Pengumuman Resmi'),
                'isi' => (string) ($data['isi'] ?? $data['content'] ?? $data['deskripsi'] ?? $fallback),
            ],
            'agenda_draft' => [
                'judul' => (string) ($data['judul'] ?? $data['nama_kegiatan'] ?? 'Agenda Kegiatan'),
                'deskripsi' => (string) ($data['deskripsi'] ?? $data['isi'] ?? $fallback),
            ],
            'umkm_draft', 'desa_draft', 'dusun_draft', 'fasilitas_draft' => [
                'deskripsi' => (string) ($data['deskripsi'] ?? $data['isi'] ?? $data['deskripsi_usaha'] ?? $data['deskripsi_fasilitas'] ?? $data['teks_hasil'] ?? $fallback),
            ],
            default => [
                'teks_hasil' => (string) ($data['teks_hasil'] ?? $data['hasil'] ?? $data['isi'] ?? $fallback),
            ],
        };
    }

    /**
     * Get metadata of the last generation.
     *
     * @return array<string, mixed>
     */
    public function getLastGenerationMeta(): array
    {
        return $this->lastGenerationMeta;
    }

    /**
     * Format raw AI model ID into user-friendly badge label.
     */
    public function formatModelLabel(?string $model): string
    {
        if (! $model) {
            return 'AI Assistant';
        }

        $map = [
            'openai/gpt-oss-120b' => 'GPT-OSS 120B',
            'groq/compound-mini' => 'Compound Mini',
            'openai/gpt-oss-20b' => 'GPT-OSS 20B',
            'llama-3.3-70b-versatile' => 'Llama 3.3 70B',
            'llama-3.1-8b-instant' => 'Llama 3.1 8B',
            'gemini-1.5-flash' => 'Gemini 1.5 Flash',
            'gemini-1.5-pro' => 'Gemini 1.5 Pro',
            'gemini-2.0-flash' => 'Gemini 2.0 Flash',
            'gemini-2.5-flash' => 'Gemini 2.5 Flash',
        ];

        if (isset($map[$model])) {
            return $map[$model];
        }

        $clean = preg_replace('/^(openai\/|groq\/|meta-llama\/|google\/)/i', '', $model);

        return strtoupper(str_replace('-', ' ', $clean));
    }
}
