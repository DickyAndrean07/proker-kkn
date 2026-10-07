<?php

namespace Tests\Feature\Admin;

use App\Models\AdminAccount;
use App\Models\Desa;
use App\Models\Dusun;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class AiAssistantTest extends TestCase
{
    use RefreshDatabase;

    private Desa $desa;

    private Dusun $dusun;

    private AdminAccount $adminDusun;

    private AdminAccount $superAdmin;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'ai.enabled' => true,
            'ai.api_key' => 'fake-gemini-key-for-testing',
            'ai.model' => 'gemini-3.5-flash-lite',
        ]);

        $this->desa = Desa::query()->forceCreate([
            'nama_desa' => 'Desa Bendung',
            'deskripsi_singkat' => 'Deskripsi Desa.',
            'alamat_kantor' => 'Jl. Desa No. 1',
            'nomor_kontak' => '081234567890',
            'nama_kepala_desa' => 'Kepala Desa',
            'jam_pelayanan' => '08.00 - 15.00',
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->dusun = Dusun::query()->forceCreate([
            'desa_id' => $this->desa->id,
            'nama_dusun' => 'Dusun Karangsawo',
            'nama_kepala_dusun' => 'Bapak Subardi',
            'status_dusun' => 'ACTIVE',
            'deskripsi_singkat' => 'Deskripsi Karangsawo.',
            'jumlah_rt' => 4,
            'jumlah_rw' => 2,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->adminDusun = AdminAccount::query()->forceCreate([
            'username' => 'admindusun',
            'password_hash' => Hash::make('Secret123!'),
            'role' => 'ADMIN_DUSUN',
            'dusun_id' => $this->dusun->id,
            'removed_at' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);

        $this->superAdmin = AdminAccount::query()->forceCreate([
            'username' => 'superadmin',
            'password_hash' => Hash::make('Secret123!'),
            'role' => 'SUPER_ADMIN',
            'dusun_id' => null,
            'removed_at' => null,
            'created_at' => CarbonImmutable::now(),
            'updated_at' => CarbonImmutable::now(),
        ]);
    }

    public function test_unauthenticated_request_is_rejected(): void
    {
        $response = $this->postJson(route('admin.ai.generate-draft'), [
            'feature' => 'pengumuman_draft',
            'mode' => 'draft',
            'notes' => 'Posyandu balita',
        ]);

        $response->assertStatus(401);
    }

    public function test_admin_dusun_can_generate_pengumuman_draft(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'judul' => 'Pemberitahuan Pelaksanaan Posyandu Balita',
                                        'isi' => 'Diberitahukan kepada seluruh warga bahwa kegiatan posyandu akan dilaksanakan.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Posyandu balita selasa depan balai dusun',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul' => 'Pemberitahuan Pelaksanaan Posyandu Balita',
                'isi' => 'Diberitahukan kepada seluruh warga bahwa kegiatan posyandu akan dilaksanakan.',
            ],
        ]);
    }

    public function test_super_admin_can_generate_agenda_draft(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'judul' => 'Musyawarah Desa',
                                        'deskripsi' => 'Rincian musyawarah desa.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'agenda_draft',
                'mode' => 'draft',
                'notes' => 'Musyawarah desa pembahasan anggaran',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul' => 'Musyawarah Desa',
                'deskripsi' => 'Rincian musyawarah desa.',
            ],
        ]);
    }

    public function test_admin_can_generate_umkm_draft(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'deskripsi' => 'Usaha produksi keripik singkong khas dusun.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'umkm_draft',
                'mode' => 'draft',
                'notes' => 'Keripik singkong renyah aneka rasa',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'deskripsi' => 'Usaha produksi keripik singkong khas dusun.',
            ],
        ]);
    }

    public function test_admin_can_improve_writing(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'teks_hasil' => 'Teks yang telah dirapikan ejaannya.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'improve_text',
                'mode' => 'rapikan',
                'existing_text' => 'teks yg blm rapi ejaanya',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'teks_hasil' => 'Teks yang telah dirapikan ejaannya.',
            ],
        ]);
    }

    public function test_markdown_wrapped_json_response_is_successfully_parsed(): void
    {
        $markdownJson = "```json\n".json_encode([
            'judul' => 'Pengumuman Kerja Bakti',
            'isi' => 'Rincian kerja bakti dusun.',
        ])."\n```";

        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => $markdownJson],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Kerja bakti hari minggu',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul' => 'Pengumuman Kerja Bakti',
                'isi' => 'Rincian kerja bakti dusun.',
            ],
        ]);
    }

    public function test_invalid_feature_returns_validation_error(): void
    {
        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'unsupported_feature',
                'mode' => 'draft',
                'notes' => 'Some notes',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['feature']);
    }

    public function test_invalid_mode_returns_validation_error(): void
    {
        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'invalid_mode',
                'notes' => 'Some notes',
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['mode']);
    }

    public function test_gemini_api_timeout_or_error_returns_graceful_json_error(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response(null, 500),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Catatan',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
        ]);
    }

    public function test_gemini_quota_exceeded_returns_rate_limit_message(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response(null, 429),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Catatan',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Batas kuota layanan AI tercapai. Silakan coba beberapa saat lagi.',
        ]);
    }

    public function test_ai_disabled_via_kill_switch_returns_error(): void
    {
        config(['ai.enabled' => false]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Catatan',
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'Layanan asisten AI sedang dinonaktifkan oleh administrator.',
        ]);
    }

    public function test_openai_compatible_provider_can_generate_draft(): void
    {
        config([
            'ai.enabled' => true,
            'ai.provider' => 'openai_compatible',
            'ai.base_url' => 'https://api.groq.com/openai/v1',
            'ai.model' => 'openai/gpt-oss-120b',
            'ai.api_key' => 'fake-openai-key',
        ]);

        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => Http::response([
                'choices' => [
                    [
                        'message' => [
                            'content' => json_encode([
                                'judul' => 'Pengumuman Via Groq',
                                'isi' => 'Isi pengumuman via generic provider.',
                            ]),
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Catatan via provider generic',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul' => 'Pengumuman Via Groq',
                'isi' => 'Isi pengumuman via generic provider.',
            ],
        ]);
    }

    public function test_can_generate_draft_using_structured_5w1h_input(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'judul' => 'Kerja Bakti Bersih Dusun',
                                        'isi' => 'Diberitahukan kepada seluruh warga RT 01-04 untuk mengikuti kerja bakti pada Minggu pagi.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'draft_length' => 'lengkap',
                'structured_input' => [
                    'who' => 'Seluruh warga RT 01-04',
                    'what' => 'Kerja Bakti Bersih Saluran Air',
                    'when' => 'Minggu, 15 Okt 2026, 08:00 WIB',
                    'where' => 'Jalan Utama Dusun Bendung',
                    'why' => 'Antisipasi musim penghujan',
                    'how' => 'Membawa cangkul dan sapu',
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul' => 'Kerja Bakti Bersih Dusun',
                'isi' => 'Diberitahukan kepada seluruh warga RT 01-04 untuk mengikuti kerja bakti pada Minggu pagi.',
            ],
        ]);
    }

    public function test_can_generate_draft_using_structured_umkm_input_with_length_option(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'deskripsi' => 'Keripik Pisang Berkah Bu Siti menyajikan keripik pisang renyah khas Dusun Bendung tanpa bahan pengawet.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'umkm_draft',
                'mode' => 'draft',
                'draft_length' => 'ringkas',
                'structured_input' => [
                    'business_name' => 'Keripik Berkah Bu Siti',
                    'product_service' => 'Keripik Pisang Aneka Rasa',
                    'usp_advantage' => 'Tanpa bahan pengawet, higienis',
                    'location' => 'Dusun Bendung RT 03',
                    'ordering_info' => 'Mulai Rp10.000/bks, hubungi WA',
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'deskripsi' => 'Keripik Pisang Berkah Bu Siti menyajikan keripik pisang renyah khas Dusun Bendung tanpa bahan pengawet.',
            ],
        ]);
    }

    public function test_can_generate_desa_profile_draft(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'deskripsi' => 'Desa Bendung adalah desa yang subur dan asri dengan hamparan persawahan hijau serta masyarakat yang mengedepankan semangat gotong royong.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'desa_draft',
                'mode' => 'draft',
                'draft_length' => 'standar',
                'structured_input' => [
                    'entity_name' => 'Desa Bendung',
                    'geographic' => 'Hamparan persawahan subur, irigasi lancar',
                    'livelihood' => 'Mayoritas petani padi dan jagung',
                    'culture' => 'Gotong royong kuat, tradisi sedekah bumi',
                    'vision' => 'Mandiri pangan dan berdaya saing',
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'deskripsi' => 'Desa Bendung adalah desa yang subur dan asri dengan hamparan persawahan hijau serta masyarakat yang mengedepankan semangat gotong royong.',
            ],
        ]);
    }

    public function test_can_generate_dusun_profile_draft(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'deskripsi' => 'Dusun Bendung I dikenal sebagai pusat kegiatan warga yang guyub dengan potensi kerajinan lokal dan areal pertanian yang produktif.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->adminDusun)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'dusun_draft',
                'mode' => 'draft',
                'draft_length' => 'ringkas',
                'structured_input' => [
                    'entity_name' => 'Dusun Bendung I',
                    'geographic' => 'Terletak di jantung desa dekat balai dusun',
                    'livelihood' => 'Pengrajin anyaman dan petani',
                    'culture' => 'Rukun dan aktif kegiatan kepemudaan',
                    'vision' => 'Dusun kreatif dan mandiri',
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'deskripsi' => 'Dusun Bendung I dikenal sebagai pusat kegiatan warga yang guyub dengan potensi kerajinan lokal dan areal pertanian yang produktif.',
            ],
        ]);
    }

    public function test_can_generate_fasilitas_draft(): void
    {
        Http::fake([
            'https://generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                [
                                    'text' => json_encode([
                                        'deskripsi' => 'Balai Dusun Bendung I merupakan sarana pertemuan serbaguna untuk musyawarah warga dengan kapasitas hingga 150 orang serta dilengkapi area parkir yang luas.',
                                    ]),
                                ],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'fasilitas_draft',
                'mode' => 'draft',
                'draft_length' => 'standar',
                'structured_input' => [
                    'facility_name' => 'Balai Dusun Bendung I',
                    'facility_category' => 'Balai Pertemuan & Pos',
                    'main_function' => 'Musyawarah warga, arisan, dan kegiatan sosial',
                    'operational_hours' => 'Setiap hari 08.00 - 21.00 WIB',
                    'amenities_capacity' => 'Kapasitas ±150 orang, parkir luas, toilet',
                    'access_rules' => 'Konfirmasi ke kepala dusun, jaga kebersihan',
                ],
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'deskripsi' => 'Balai Dusun Bendung I merupakan sarana pertemuan serbaguna untuk musyawarah warga dengan kapasitas hingga 150 orang serta dilengkapi area parkir yang luas.',
            ],
        ]);
    }

    public function test_auto_failover_when_primary_model_encounters_rate_limit(): void
    {
        config([
            'ai.provider' => 'groq',
            'ai.model' => 'openai/gpt-oss-120b',
            'ai.api_key' => 'dummy-key',
            'ai.fallback_models' => ['groq/compound-mini'],
        ]);

        $attempt = 0;
        Http::fake([
            'https://api.groq.com/openai/v1/chat/completions' => function ($request) use (&$attempt) {
                $attempt++;
                $data = json_decode($request->body(), true);

                if ($attempt === 1) {
                    $this->assertEquals('openai/gpt-oss-120b', $data['model']);

                    return Http::response([
                        'error' => ['message' => 'Rate limit reached on TPM'],
                    ], 429);
                }

                $this->assertEquals('groq/compound-mini', $data['model']);

                return Http::response([
                    'choices' => [
                        [
                            'message' => [
                                'content' => json_encode([
                                    'judul' => 'Pengumuman Darurat',
                                    'isi' => 'Konten berhasil di-generate via fallback model.',
                                ]),
                            ],
                        ],
                    ],
                ], 200);
            },
        ]);

        $response = $this->actingAs($this->superAdmin)
            ->postJson(route('admin.ai.generate-draft'), [
                'feature' => 'pengumuman_draft',
                'mode' => 'draft',
                'notes' => 'Kerja bakti pembersihan saluran air',
            ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => [
                'judul' => 'Pengumuman Darurat',
                'isi' => 'Konten berhasil di-generate via fallback model.',
            ],
            'meta' => [
                'model' => 'groq/compound-mini',
                'model_label' => 'Compound Mini',
                'is_fallback' => true,
                'attempt' => 2,
            ],
        ]);
        $this->assertEquals(2, $attempt);
    }
}


