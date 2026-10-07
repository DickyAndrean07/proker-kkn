<div class="ai-modal-backdrop" id="aiModalBackdrop" data-endpoint="{{ url('/admin/ai/generate-draft') }}" style="display: none;" aria-hidden="true">
    <div class="ai-modal-dialog" role="dialog" aria-labelledby="aiModalTitle" aria-modal="true">
        <div class="ai-modal-header">
            <div class="ai-modal-header-brand">
                <span class="ai-sparkle-badge" aria-hidden="true">✨</span>
                <div>
                    <h3 class="ai-modal-title" id="aiModalTitle">Asisten Penulisan AI</h3>
                    <p class="ai-modal-subtitle" id="aiModalSubtitle">Bantu buat dan sempurnakan draf teks secara instan</p>
                </div>
            </div>
            <button type="button" class="ai-modal-close" id="aiModalCloseBtn" aria-label="Tutup Dialog">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="ai-modal-body">
            <!-- Mode Selector Tabs -->
            <div class="ai-mode-pills" role="tablist" aria-label="Pilihan Mode AI">
                <button type="button" class="ai-mode-pill is-active" data-mode="draft">Buat Draf Baru</button>
                <button type="button" class="ai-mode-pill" data-mode="rapikan">Rapikan Ejaan</button>
                <button type="button" class="ai-mode-pill" data-mode="formal">Lebih Formal</button>
                <button type="button" class="ai-mode-pill" data-mode="persingkat">Persingkat</button>
            </div>

            <!-- Notice / Boundary Information -->
            <div class="ai-safety-notice">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
                </svg>
                <span>AI hanya menyusun draf dari catatan Anda. Tanggal, lokasi, dan status tetap diatur manual.</span>
            </div>

            <!-- Input Fields -->
            <div class="ai-input-section" id="aiInputSection">
                <!-- Sub-toggle for Draft Mode: Terstruktur vs Catatan Bebas -->
                <div class="ai-input-type-wrapper" id="aiInputTypeWrapper">
                    <div class="ai-input-type-toggle" role="tablist" aria-label="Format Input">
                        <button type="button" class="ai-type-btn is-active" id="aiTypeStructuredBtn" data-type="structured">
                            <span class="ai-type-icon">📋</span> Mode Terpadu (Terstruktur)
                        </button>
                        <button type="button" class="ai-type-btn" id="aiTypeFreeBtn" data-type="free">
                            <span class="ai-type-icon">📝</span> Catatan Bebas (Cepat)
                        </button>
                    </div>
                </div>

                <!-- Structured 5W+1H Form (for Pengumuman & Agenda) -->
                <div class="ai-structured-form" id="ai5w1hFormSection">
                    <div class="ai-form-grid">
                        <div class="form-group">
                            <label for="ai5wWho" class="form-label text-xs">
                                <strong>WHO</strong> (Sasaran / Penyelenggara)
                            </label>
                            <input type="text" id="ai5wWho" class="form-input form-input-sm" placeholder="Contoh: Seluruh warga RT 01-04 / Karang Taruna">
                        </div>
                        <div class="form-group">
                            <label for="ai5wWhat" class="form-label text-xs">
                                <strong>WHAT</strong> (Perihal / Nama Kegiatan) <span class="required-mark">*</span>
                            </label>
                            <input type="text" id="ai5wWhat" class="form-input form-input-sm" placeholder="Contoh: Penyaluran Bansos BLT / Kerja Bakti">
                        </div>
                        <div class="form-group">
                            <label for="ai5wWhen" class="form-label text-xs">
                                <strong>WHEN</strong> (Hari, Tanggal & Waktu)
                            </label>
                            <input type="text" id="ai5wWhen" class="form-input form-input-sm" placeholder="Contoh: Minggu, 15 Okt 2026, 08:00 WIB">
                        </div>
                        <div class="form-group">
                            <label for="ai5wWhere" class="form-label text-xs">
                                <strong>WHERE</strong> (Lokasi / Tempat Gedung)
                            </label>
                            <input type="text" id="ai5wWhere" class="form-input form-input-sm" placeholder="Contoh: Balai Dusun Bendung">
                        </div>
                        <div class="form-group">
                            <label for="ai5wWhy" class="form-label text-xs">
                                <strong>WHY</strong> (Tujuan / Urgensi Acara)
                            </label>
                            <input type="text" id="ai5wWhy" class="form-input form-input-sm" placeholder="Contoh: Program ketahanan pangan / Antisipasi hujan">
                        </div>
                        <div class="form-group">
                            <label for="ai5wHow" class="form-label text-xs">
                                <strong>HOW</strong> (Syarat / Ketentuan / Perlengkapan)
                            </label>
                            <input type="text" id="ai5wHow" class="form-input form-input-sm" placeholder="Contoh: Bawa KTP & KK asli / Bawa cangkul">
                        </div>
                    </div>
                </div>

                <!-- Structured UMKM Form -->
                <div class="ai-structured-form" id="aiUmkmFormSection" style="display: none;">
                    <div class="ai-form-grid">
                        <div class="form-group">
                            <label for="aiUmkmName" class="form-label text-xs">
                                <strong>Nama Usaha / Pemilik</strong>
                            </label>
                            <input type="text" id="aiUmkmName" class="form-input form-input-sm" placeholder="Contoh: Keripik Berkah Bu Siti">
                        </div>
                        <div class="form-group">
                            <label for="aiUmkmProduct" class="form-label text-xs">
                                <strong>Produk / Layanan Unggulan</strong> <span class="required-mark">*</span>
                            </label>
                            <input type="text" id="aiUmkmProduct" class="form-input form-input-sm" placeholder="Contoh: Keripik Pisang Aneka Rasa & Madu Alami">
                        </div>
                        <div class="form-group span-2">
                            <label for="aiUmkmUsp" class="form-label text-xs">
                                <strong>Keunggulan & Ciri Khas</strong> (USP / Kelebihan)
                            </label>
                            <input type="text" id="aiUmkmUsp" class="form-input form-input-sm" placeholder="Contoh: Tanpa bahan pengawet, higienis, bumbu rempah alami pilihan">
                        </div>
                        <div class="form-group">
                            <label for="aiUmkmLocation" class="form-label text-xs">
                                <strong>Lokasi / Dusun Produksi</strong>
                            </label>
                            <input type="text" id="aiUmkmLocation" class="form-input form-input-sm" placeholder="Contoh: Dusun Bendung RT 03">
                        </div>
                        <div class="form-group">
                            <label for="aiUmkmOrder" class="form-label text-xs">
                                <strong>Pemesanan, Kontak & Harga</strong>
                            </label>
                            <input type="text" id="aiUmkmOrder" class="form-input form-input-sm" placeholder="Contoh: Mulai Rp10.000/bks, siap COD & kirim">
                        </div>
                    </div>
                </div>

                <!-- Structured Profile Form (for Desa & Dusun) -->
                <div class="ai-structured-form" id="aiProfileFormSection" style="display: none;">
                    <div class="ai-form-grid">
                        <div class="form-group span-2">
                            <label for="aiProfileEntityName" class="form-label text-xs">
                                <strong>Nama Desa / Dusun</strong>
                            </label>
                            <input type="text" id="aiProfileEntityName" class="form-input form-input-sm" placeholder="Contoh: Desa Bendung / Dusun Bendung I">
                        </div>
                        <div class="form-group">
                            <label for="aiProfileGeographic" class="form-label text-xs">
                                <strong>Karakteristik Wilayah / Lingkungan Alam</strong>
                            </label>
                            <input type="text" id="aiProfileGeographic" class="form-input form-input-sm" placeholder="Contoh: Hamparan persawahan subur, perbukitan asri, irigasi lancar">
                        </div>
                        <div class="form-group">
                            <label for="aiProfileLivelihood" class="form-label text-xs">
                                <strong>Potensi Utama & Mata Pencaharian</strong>
                            </label>
                            <input type="text" id="aiProfileLivelihood" class="form-input form-input-sm" placeholder="Contoh: Mayoritas bertani padi & jagung, pengrajin anyaman, sentra UMKM">
                        </div>
                        <div class="form-group">
                            <label for="aiProfileCulture" class="form-label text-xs">
                                <strong>Kehidupan Warga & Kerukunan</strong>
                            </label>
                            <input type="text" id="aiProfileCulture" class="form-input form-input-sm" placeholder="Contoh: Gotong royong kental, tradisi sedekah bumi, lingkungan aman dan asri">
                        </div>
                        <div class="form-group">
                            <label for="aiProfileVision" class="form-label text-xs">
                                <strong>Ciri Khas & Semangat Wilayah</strong>
                            </label>
                            <input type="text" id="aiProfileVision" class="form-input form-input-sm" placeholder="Contoh: Menuju wilayah mandiri pangan, ramah warga, dan berdaya saing">
                        </div>
                    </div>
                </div>

                <!-- Structured Fasilitas Form -->
                <div class="ai-structured-form" id="aiFasilitasFormSection" style="display: none;">
                    <div class="ai-form-grid">
                        <div class="form-group">
                            <label for="aiFacilityName" class="form-label text-xs">
                                <strong>Nama Fasilitas</strong> <span class="required-mark">*</span>
                            </label>
                            <input type="text" id="aiFacilityName" class="form-input form-input-sm" placeholder="Contoh: Balai Pertemuan Dusun Bendung I">
                        </div>
                        <div class="form-group">
                            <label for="aiFacilityCategory" class="form-label text-xs">
                                <strong>Kategori Fasilitas</strong>
                            </label>
                            <input type="text" id="aiFacilityCategory" class="form-input form-input-sm" placeholder="Contoh: Balai Pertemuan & Pos / Sarana Ibadah">
                        </div>
                        <div class="form-group span-2">
                            <label for="aiFacilityFunction" class="form-label text-xs">
                                <strong>Fungsi Utama & Layanan Publik</strong> <span class="required-mark">*</span>
                            </label>
                            <input type="text" id="aiFacilityFunction" class="form-input form-input-sm" placeholder="Contoh: Pusat musyawarah warga, kegiatan arisan, posko tanggap darurat, dan kegiatan sosial">
                        </div>
                        <div class="form-group">
                            <label for="aiFacilityHours" class="form-label text-xs">
                                <strong>Jam Pelayanan / Waktu Buka</strong> <span class="optional-tag">(Opsional)</span>
                            </label>
                            <input type="text" id="aiFacilityHours" class="form-input form-input-sm" placeholder="Contoh: Setiap hari 08.00 - 21.00 WIB / Sesuai jadwal kegiatan">
                        </div>
                        <div class="form-group">
                            <label for="aiFacilityAmenities" class="form-label text-xs">
                                <strong>Sarana Pendukung & Daya Tampung</strong> <span class="optional-tag">(Opsional)</span>
                            </label>
                            <input type="text" id="aiFacilityAmenities" class="form-input form-input-sm" placeholder="Contoh: Kapasitas ±150 orang, sound system, toilet, parkir motor luas">
                        </div>
                        <div class="form-group span-2">
                            <label for="aiFacilityRules" class="form-label text-xs">
                                <strong>Ketentuan Akses & Tata Tertib Warga</strong> <span class="optional-tag">(Opsional)</span>
                            </label>
                            <input type="text" id="aiFacilityRules" class="form-input form-input-sm" placeholder="Contoh: Bebas digunakan warga dengan konfirmasi ke kepala dusun, wajib menjaga kebersihan">
                        </div>
                    </div>
                </div>

                <!-- Free Notes Input Group -->
                <div class="form-group" id="aiNotesGroup" style="display: none;">
                    <label for="aiNotesInput" class="form-label">Catatan / Poin Fakta Mentah <span class="required-mark">*</span></label>
                    <textarea 
                        id="aiNotesInput" 
                        class="form-textarea" 
                        rows="4" 
                        placeholder="Contoh: Posyandu balita selasa depan balai dusun jam 9 pagi, ada penimbangan dan imunisasi..."
                        maxlength="2500"
                    ></textarea>
                    <span class="field-hint">Tuliskan poin-poin yang ingin disampaikan. AI akan merapikannya menjadi kalimat resmi.</span>
                </div>

                <!-- Length Directive Pills -->
                <div class="ai-length-group" id="aiLengthGroup">
                    <label class="form-label text-xs">Gaya Panjang Draf:</label>
                    <div class="ai-length-pills">
                        <button type="button" class="ai-length-pill" data-length="ringkas">Ringkas & Padat</button>
                        <button type="button" class="ai-length-pill is-active" data-length="standar">Standar</button>
                        <button type="button" class="ai-length-pill" data-length="lengkap">Lengkap & Terperinci</button>
                    </div>
                </div>

                <!-- Existing Text Group (for improve modes) -->
                <div class="form-group" id="aiExistingTextGroup" style="display: none;">
                    <label for="aiExistingTextInput" class="form-label">Teks Yang Ingin Disempurnakan</label>
                    <textarea 
                        id="aiExistingTextInput" 
                        class="form-textarea" 
                        rows="4" 
                        placeholder="Teks dari form yang ingin dirapikan atau diubah gaya bahasanya..."
                        maxlength="4500"
                    ></textarea>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div class="ai-loading-state" id="aiLoadingState" style="display: none;">
                <div class="ai-spinner"></div>
                <p>Sedang menyusun draf dengan AI...</p>
                <small>Mohon tunggu beberapa detik</small>
            </div>

            <!-- Error Box -->
            <div class="ai-error-box" id="aiErrorBox" style="display: none;" role="alert">
                <p id="aiErrorMessage">Layanan bantuan AI sedang tidak tersedia.</p>
            </div>

            <!-- Result / Preview Section -->
            <div class="ai-result-section" id="aiResultSection" style="display: none;">
                <div class="ai-result-header">
                    <span class="ai-result-badge">Pratinjau Hasil Draf AI</span>
                    <span class="ai-model-badge" id="aiModelBadge" style="display: none;"></span>
                </div>
                
                <div class="form-group" id="aiResultTitleGroup" style="display: none;">
                    <label class="form-label text-sm">Usulan Judul:</label>
                    <div class="ai-preview-box" id="aiPreviewTitle"></div>
                </div>

                <div class="form-group" id="aiResultTextGroup">
                    <label class="form-label text-sm">Usulan Teks:</label>
                    <div class="ai-preview-box ai-preview-content" id="aiPreviewContent"></div>
                </div>
            </div>
        </div>

        <div class="ai-modal-footer">
            <div class="ai-footer-left">
                <button type="button" class="btn btn-secondary" id="aiCancelBtn">Batal</button>
            </div>
            <div class="ai-footer-right">
                <button type="button" class="btn btn-secondary" id="aiRetryBtn" style="display: none;">Buat Ulang</button>
                <button type="button" class="btn btn-primary" id="aiGenerateBtn">
                    <span>✨ Generate Draf</span>
                </button>
                <button type="button" class="btn btn-primary btn-apply" id="aiApplyBtn" style="display: none;">
                    <span>✓ Terapkan ke Form</span>
                </button>
            </div>
        </div>
    </div>
</div>
