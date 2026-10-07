/**
 * AI Writing Assistant Frontend Client (Vanilla JS)
 * Non-blocking, Human-in-the-loop draft generator.
 * Supports 5W+1H structured inputs and tailored UMKM business profiling.
 */

export function initAiAssistant() {
    let currentFeature = 'pengumuman_draft';
    let currentMode = 'draft';
    let currentInputType = 'structured';
    let currentLength = 'standar';
    let targetTitleElement = null;
    let targetTextElement = null;
    let generatedData = null;

    function getModalElements() {
        const modalBackdrop = document.getElementById('aiModalBackdrop');
        if (!modalBackdrop) return null;

        return {
            modalBackdrop,
            closeBtn: document.getElementById('aiModalCloseBtn'),
            cancelBtn: document.getElementById('aiCancelBtn'),
            generateBtn: document.getElementById('aiGenerateBtn'),
            applyBtn: document.getElementById('aiApplyBtn'),
            retryBtn: document.getElementById('aiRetryBtn'),
            inputSection: document.getElementById('aiInputSection'),
            inputTypeWrapper: document.getElementById('aiInputTypeWrapper'),
            typeStructuredBtn: document.getElementById('aiTypeStructuredBtn'),
            typeFreeBtn: document.getElementById('aiTypeFreeBtn'),
            form5w1hSection: document.getElementById('ai5w1hFormSection'),
            formUmkmSection: document.getElementById('aiUmkmFormSection'),
            notesGroup: document.getElementById('aiNotesGroup'),
            notesInput: document.getElementById('aiNotesInput'),
            lengthGroup: document.getElementById('aiLengthGroup'),
            lengthPills: document.querySelectorAll('.ai-length-pill'),
            existingTextGroup: document.getElementById('aiExistingTextGroup'),
            existingTextInput: document.getElementById('aiExistingTextInput'),
            loadingState: document.getElementById('aiLoadingState'),
            errorBox: document.getElementById('aiErrorBox'),
            errorMessage: document.getElementById('aiErrorMessage'),
            resultSection: document.getElementById('aiResultSection'),
            resultTitleGroup: document.getElementById('aiResultTitleGroup'),
            previewTitle: document.getElementById('aiPreviewTitle'),
            previewContent: document.getElementById('aiPreviewContent'),
            modelBadge: document.getElementById('aiModelBadge'),
            modePills: document.querySelectorAll('.ai-mode-pill'),
            // 5W1H Inputs
            input5wWho: document.getElementById('ai5wWho'),
            input5wWhat: document.getElementById('ai5wWhat'),
            input5wWhen: document.getElementById('ai5wWhen'),
            input5wWhere: document.getElementById('ai5wWhere'),
            input5wWhy: document.getElementById('ai5wWhy'),
            input5wHow: document.getElementById('ai5wHow'),
            // UMKM Inputs
            inputUmkmName: document.getElementById('aiUmkmName'),
            inputUmkmProduct: document.getElementById('aiUmkmProduct'),
            inputUmkmUsp: document.getElementById('aiUmkmUsp'),
            inputUmkmLocation: document.getElementById('aiUmkmLocation'),
            inputUmkmOrder: document.getElementById('aiUmkmOrder'),
            // Profile Desa / Dusun Inputs
            formProfileSection: document.getElementById('aiProfileFormSection'),
            inputProfileEntityName: document.getElementById('aiProfileEntityName'),
            inputProfileGeographic: document.getElementById('aiProfileGeographic'),
            inputProfileLivelihood: document.getElementById('aiProfileLivelihood'),
            inputProfileCulture: document.getElementById('aiProfileCulture'),
            inputProfileVision: document.getElementById('aiProfileVision'),
            // Fasilitas Inputs
            formFasilitasSection: document.getElementById('aiFasilitasFormSection'),
            inputFacilityName: document.getElementById('aiFacilityName'),
            inputFacilityCategory: document.getElementById('aiFacilityCategory'),
            inputFacilityFunction: document.getElementById('aiFacilityFunction'),
            inputFacilityHours: document.getElementById('aiFacilityHours'),
            inputFacilityAmenities: document.getElementById('aiFacilityAmenities'),
            inputFacilityRules: document.getElementById('aiFacilityRules'),
        };
    }

    function syncInputVisibility(elements) {
        if (!elements) return;

        if (currentMode === 'draft') {
            if (elements.inputTypeWrapper) elements.inputTypeWrapper.style.display = 'block';
            if (elements.lengthGroup) elements.lengthGroup.style.display = 'block';

            if (currentInputType === 'structured') {
                if (elements.notesGroup) elements.notesGroup.style.display = 'none';
                if (currentFeature === 'umkm_draft') {
                    if (elements.form5w1hSection) elements.form5w1hSection.style.display = 'none';
                    if (elements.formUmkmSection) elements.formUmkmSection.style.display = 'block';
                    if (elements.formProfileSection) elements.formProfileSection.style.display = 'none';
                    if (elements.formFasilitasSection) elements.formFasilitasSection.style.display = 'none';
                } else if (currentFeature === 'desa_draft' || currentFeature === 'dusun_draft') {
                    if (elements.form5w1hSection) elements.form5w1hSection.style.display = 'none';
                    if (elements.formUmkmSection) elements.formUmkmSection.style.display = 'none';
                    if (elements.formProfileSection) elements.formProfileSection.style.display = 'block';
                    if (elements.formFasilitasSection) elements.formFasilitasSection.style.display = 'none';
                } else if (currentFeature === 'fasilitas_draft') {
                    if (elements.form5w1hSection) elements.form5w1hSection.style.display = 'none';
                    if (elements.formUmkmSection) elements.formUmkmSection.style.display = 'none';
                    if (elements.formProfileSection) elements.formProfileSection.style.display = 'none';
                    if (elements.formFasilitasSection) elements.formFasilitasSection.style.display = 'block';
                } else {
                    if (elements.form5w1hSection) elements.form5w1hSection.style.display = 'block';
                    if (elements.formUmkmSection) elements.formUmkmSection.style.display = 'none';
                    if (elements.formProfileSection) elements.formProfileSection.style.display = 'none';
                    if (elements.formFasilitasSection) elements.formFasilitasSection.style.display = 'none';
                }
            } else {
                if (elements.form5w1hSection) elements.form5w1hSection.style.display = 'none';
                if (elements.formUmkmSection) elements.formUmkmSection.style.display = 'none';
                if (elements.formProfileSection) elements.formProfileSection.style.display = 'none';
                if (elements.formFasilitasSection) elements.formFasilitasSection.style.display = 'none';
                if (elements.notesGroup) elements.notesGroup.style.display = 'block';
            }

            if (elements.existingTextGroup) {
                elements.existingTextGroup.style.display = elements.existingTextInput?.value.trim() ? 'block' : 'none';
            }
        } else {
            // Improve text modes (rapikan, formal, persingkat)
            if (elements.inputTypeWrapper) elements.inputTypeWrapper.style.display = 'none';
            if (elements.form5w1hSection) elements.form5w1hSection.style.display = 'none';
            if (elements.formUmkmSection) elements.formUmkmSection.style.display = 'none';
            if (elements.formProfileSection) elements.formProfileSection.style.display = 'none';
            if (elements.formFasilitasSection) elements.formFasilitasSection.style.display = 'none';
            if (elements.lengthGroup) elements.lengthGroup.style.display = 'none';
            if (elements.existingTextGroup) elements.existingTextGroup.style.display = 'block';
            if (elements.notesGroup) elements.notesGroup.style.display = 'block';
        }
    }

    function resetModal(elements) {
        generatedData = null;
        if (elements.inputSection) elements.inputSection.style.display = 'block';
        if (elements.loadingState) elements.loadingState.style.display = 'none';
        if (elements.errorBox) elements.errorBox.style.display = 'none';
        if (elements.resultSection) elements.resultSection.style.display = 'none';
        if (elements.modelBadge) elements.modelBadge.style.display = 'none';
        if (elements.generateBtn) {
            elements.generateBtn.style.display = 'inline-flex';
            elements.generateBtn.disabled = false;
        }
        if (elements.applyBtn) elements.applyBtn.style.display = 'none';
        if (elements.retryBtn) elements.retryBtn.style.display = 'none';

        syncInputVisibility(elements);
    }

    function closeModal() {
        const elements = getModalElements();
        if (elements?.modalBackdrop) {
            elements.modalBackdrop.style.display = 'none';
            elements.modalBackdrop.setAttribute('aria-hidden', 'true');
        }
    }

    function openModal(triggerBtn) {
        const elements = getModalElements();
        if (!elements) {
            console.error('[AI Assistant] Modal element #aiModalBackdrop not found in DOM.');
            return;
        }

        currentFeature = triggerBtn.dataset.aiFeature || 'pengumuman_draft';
        const titleId = triggerBtn.dataset.targetTitle;
        const textId = triggerBtn.dataset.targetText;

        targetTitleElement = titleId ? document.getElementById(titleId) : null;
        targetTextElement = textId ? document.getElementById(textId) : null;

        const existingVal = targetTextElement ? targetTextElement.value : '';
        if (elements.existingTextInput) {
            elements.existingTextInput.value = existingVal;
        }

        // Auto pre-fill UMKM or Title into what/product field if empty
        if (currentFeature === 'umkm_draft' && elements.inputUmkmProduct && !elements.inputUmkmProduct.value.trim() && targetTitleElement?.value) {
            elements.inputUmkmName.value = targetTitleElement.value;
        } else if ((currentFeature === 'pengumuman_draft' || currentFeature === 'agenda_draft') && elements.input5wWhat && !elements.input5wWhat.value.trim() && targetTitleElement?.value) {
            elements.input5wWhat.value = targetTitleElement.value;
        } else if ((currentFeature === 'desa_draft' || currentFeature === 'dusun_draft') && elements.inputProfileEntityName) {
            const entityName = triggerBtn.dataset.entityName || (targetTitleElement?.value ?? '');
            if (entityName) {
                elements.inputProfileEntityName.value = entityName;
            }
        } else if (currentFeature === 'fasilitas_draft' && elements.inputFacilityName) {
            // Auto pre-fill facility name if available from target title element or #nama in parent form
            const formNameInput = targetTitleElement || document.getElementById('nama') || document.querySelector('input[name="nama"]');
            if (formNameInput && formNameInput.value.trim() && !elements.inputFacilityName.value.trim()) {
                elements.inputFacilityName.value = formNameInput.value.trim();
            }
            // Auto pre-fill facility category from parent form select if available
            const formCategorySelect = document.getElementById('kategori_fasilitas_id') || document.querySelector('select[name="kategori_fasilitas_id"]');
            if (formCategorySelect && formCategorySelect.selectedIndex > 0 && elements.inputFacilityCategory && !elements.inputFacilityCategory.value.trim()) {
                const selectedOptionText = formCategorySelect.options[formCategorySelect.selectedIndex]?.text?.trim();
                if (selectedOptionText && !selectedOptionText.startsWith('--')) {
                    elements.inputFacilityCategory.value = selectedOptionText;
                }
            }
        }

        resetModal(elements);
        elements.modalBackdrop.style.display = 'flex';
        elements.modalBackdrop.setAttribute('aria-hidden', 'false');

        // Auto focus appropriate input
        setTimeout(() => {
            if (currentMode === 'draft') {
                if (currentInputType === 'structured') {
                    if (currentFeature === 'umkm_draft') {
                        elements.inputUmkmName?.focus();
                    } else if (currentFeature === 'desa_draft' || currentFeature === 'dusun_draft') {
                        elements.inputProfileGeographic?.focus();
                    } else if (currentFeature === 'fasilitas_draft') {
                        elements.inputFacilityFunction?.focus();
                    } else {
                        elements.input5wWhat?.focus();
                    }
                } else {
                    elements.notesInput?.focus();
                }
            } else {
                elements.existingTextInput?.focus();
            }
        }, 60);
    }

    // Global Document Click Delegation for Trigger Buttons
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('.btn-ai-assist');
        if (trigger) {
            e.preventDefault();
            e.stopPropagation();
            openModal(trigger);
            return;
        }

        const closeTrigger = e.target.closest('#aiModalCloseBtn, #aiCancelBtn');
        if (closeTrigger) {
            e.preventDefault();
            closeModal();
            return;
        }

        const modalBackdrop = document.getElementById('aiModalBackdrop');
        if (modalBackdrop && e.target === modalBackdrop) {
            closeModal();
            return;
        }

        // Mode Selector (Draft, Rapikan, Formal, Persingkat)
        const pill = e.target.closest('.ai-mode-pill');
        if (pill) {
            e.preventDefault();
            const elements = getModalElements();
            if (!elements) return;

            elements.modePills.forEach(p => p.classList.remove('is-active'));
            pill.classList.add('is-active');
            currentMode = pill.dataset.mode || 'draft';
            syncInputVisibility(elements);
            return;
        }

        // Input Type Selector (Structured vs Free Notes)
        const typeBtn = e.target.closest('.ai-type-btn');
        if (typeBtn) {
            e.preventDefault();
            const elements = getModalElements();
            if (!elements) return;

            document.querySelectorAll('.ai-type-btn').forEach(b => b.classList.remove('is-active'));
            typeBtn.classList.add('is-active');
            currentInputType = typeBtn.dataset.type || 'structured';
            syncInputVisibility(elements);
            return;
        }

        // Draft Length Selector (Ringkas, Standar, Lengkap)
        const lengthBtn = e.target.closest('.ai-length-pill');
        if (lengthBtn) {
            e.preventDefault();
            const elements = getModalElements();
            if (!elements) return;

            elements.lengthPills.forEach(b => b.classList.remove('is-active'));
            lengthBtn.classList.add('is-active');
            currentLength = lengthBtn.dataset.length || 'standar';
            return;
        }

        const generateBtn = e.target.closest('#aiGenerateBtn');
        if (generateBtn) {
            e.preventDefault();
            handleGenerate();
            return;
        }

        const retryBtn = e.target.closest('#aiRetryBtn');
        if (retryBtn) {
            e.preventDefault();
            const elements = getModalElements();
            if (elements) resetModal(elements);
            return;
        }

        const applyBtn = e.target.closest('#aiApplyBtn');
        if (applyBtn) {
            e.preventDefault();
            handleApply();
            return;
        }
    });

    async function handleGenerate() {
        const elements = getModalElements();
        if (!elements) return;

        let notes = elements.notesInput?.value.trim() || '';
        const existingText = elements.existingTextInput?.value.trim() || '';
        let structuredInput = null;

        if (currentMode === 'draft') {
            if (currentInputType === 'structured') {
                if (currentFeature === 'umkm_draft') {
                    const businessName = elements.inputUmkmName?.value.trim() || '';
                    const productService = elements.inputUmkmProduct?.value.trim() || '';
                    const uspAdvantage = elements.inputUmkmUsp?.value.trim() || '';
                    const location = elements.inputUmkmLocation?.value.trim() || '';
                    const orderingInfo = elements.inputUmkmOrder?.value.trim() || '';

                    if (!productService && !businessName && !uspAdvantage) {
                        showError(elements, 'Silakan isi minimal Produk / Layanan Unggulan atau Nama Usaha.');
                        elements.inputUmkmProduct?.focus();
                        return;
                    }

                    structuredInput = {
                        business_name: businessName,
                        product_service: productService,
                        usp_advantage: uspAdvantage,
                        location: location,
                        ordering_info: orderingInfo,
                    };
                } else if (currentFeature === 'desa_draft' || currentFeature === 'dusun_draft') {
                    const entityName = elements.inputProfileEntityName?.value.trim() || '';
                    const geographic = elements.inputProfileGeographic?.value.trim() || '';
                    const livelihood = elements.inputProfileLivelihood?.value.trim() || '';
                    const culture = elements.inputProfileCulture?.value.trim() || '';
                    const vision = elements.inputProfileVision?.value.trim() || '';

                    if (!entityName && !geographic && !livelihood && !culture && !vision) {
                        showError(elements, 'Silakan isi minimal salah satu poin karakteristik, potensi, atau nama wilayah.');
                        elements.inputProfileGeographic?.focus();
                        return;
                    }

                    structuredInput = {
                        entity_name: entityName,
                        geographic: geographic,
                        livelihood: livelihood,
                        culture: culture,
                        vision: vision,
                    };
                } else if (currentFeature === 'fasilitas_draft') {
                    const facilityName = elements.inputFacilityName?.value.trim() || '';
                    const facilityCategory = elements.inputFacilityCategory?.value.trim() || '';
                    const mainFunction = elements.inputFacilityFunction?.value.trim() || '';
                    const operationalHours = elements.inputFacilityHours?.value.trim() || '';
                    const amenitiesCapacity = elements.inputFacilityAmenities?.value.trim() || '';
                    const accessRules = elements.inputFacilityRules?.value.trim() || '';

                    if (!facilityName && !mainFunction && !amenitiesCapacity) {
                        showError(elements, 'Silakan isi minimal Nama Fasilitas atau Fungsi Utama & Layanan Publik.');
                        elements.inputFacilityFunction?.focus();
                        return;
                    }

                    structuredInput = {
                        facility_name: facilityName,
                        facility_category: facilityCategory,
                        main_function: mainFunction,
                        operational_hours: operationalHours,
                        amenities_capacity: amenitiesCapacity,
                        access_rules: accessRules,
                    };
                } else {
                    const who = elements.input5wWho?.value.trim() || '';
                    const what = elements.input5wWhat?.value.trim() || '';
                    const when = elements.input5wWhen?.value.trim() || '';
                    const where = elements.input5wWhere?.value.trim() || '';
                    const why = elements.input5wWhy?.value.trim() || '';
                    const how = elements.input5wHow?.value.trim() || '';

                    if (!what && !who && !where) {
                        showError(elements, 'Silakan isi minimal perihal/kegiatan pada kolom WHAT.');
                        elements.input5wWhat?.focus();
                        return;
                    }

                    structuredInput = {
                        who: who,
                        what: what,
                        when: when,
                        where: where,
                        why: why,
                        how: how,
                    };
                }
            } else {
                if (!notes) {
                    showError(elements, 'Silakan isi catatan fakta mentah.');
                    elements.notesInput?.focus();
                    return;
                }
            }
        } else {
            // Text improvement mode
            if (!existingText && !notes) {
                showError(elements, 'Silakan isi teks yang ingin disempurnakan.');
                elements.existingTextInput?.focus();
                return;
            }
        }

        if (elements.inputSection) elements.inputSection.style.display = 'none';
        if (elements.resultSection) elements.resultSection.style.display = 'none';
        if (elements.errorBox) elements.errorBox.style.display = 'none';
        if (elements.loadingState) elements.loadingState.style.display = 'block';
        if (elements.generateBtn) elements.generateBtn.disabled = true;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const endpoint = elements.modalBackdrop?.dataset.endpoint || '/admin/ai/generate-draft';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                },
                body: JSON.stringify({
                    feature: currentFeature,
                    mode: currentMode,
                    notes: notes || null,
                    existing_text: existingText || null,
                    structured_input: structuredInput || null,
                    draft_length: currentLength || 'standar',
                }),
            });

            const result = await response.json();

            if (elements.loadingState) elements.loadingState.style.display = 'none';

            if (!response.ok || !result.success) {
                if (elements.inputSection) elements.inputSection.style.display = 'block';
                showError(elements, result.message || 'Layanan bantuan AI sedang tidak tersedia.');
                if (elements.generateBtn) elements.generateBtn.disabled = false;
                return;
            }

            generatedData = result.data;
            renderPreview(elements, result.data, result.meta);

        } catch (err) {
            if (elements.loadingState) elements.loadingState.style.display = 'none';
            if (elements.inputSection) elements.inputSection.style.display = 'block';
            showError(elements, 'Gagal terhubung dengan server. Silakan coba lagi atau isi form manual.');
            if (elements.generateBtn) elements.generateBtn.disabled = false;
        }
    }

    function showError(elements, message) {
        if (elements.errorBox && elements.errorMessage) {
            elements.errorBox.style.display = 'block';
            elements.errorMessage.textContent = message;
        }
    }

    function renderPreview(elements, data, meta) {
        if (elements.resultSection) elements.resultSection.style.display = 'block';
        if (elements.generateBtn) elements.generateBtn.style.display = 'none';
        if (elements.applyBtn) elements.applyBtn.style.display = 'inline-flex';
        if (elements.retryBtn) elements.retryBtn.style.display = 'inline-flex';

        // Render AI Model Badge
        if (elements.modelBadge && meta) {
            elements.modelBadge.style.display = 'inline-flex';
            const latencyStr = (meta.latency_seconds !== undefined && meta.latency_seconds !== null) ? `${meta.latency_seconds}s` : '0.8s';
            if (meta.is_fallback) {
                elements.modelBadge.className = 'ai-model-badge is-fallback';
                elements.modelBadge.textContent = `⚡ Model: ${meta.model_label || 'Compound Mini'} (Dialihkan otomatis • Kuota Aman) • ${latencyStr}`;
            } else {
                elements.modelBadge.className = 'ai-model-badge is-primary';
                elements.modelBadge.textContent = `✨ Model: ${meta.model_label || 'GPT-OSS 120B'} • ${latencyStr}`;
            }
        } else if (elements.modelBadge) {
            elements.modelBadge.style.display = 'none';
        }

        if (data.judul && targetTitleElement && elements.previewTitle && elements.resultTitleGroup) {
            elements.resultTitleGroup.style.display = 'block';
            elements.previewTitle.textContent = data.judul;
        } else if (elements.resultTitleGroup) {
            elements.resultTitleGroup.style.display = 'none';
        }

        const textOutput = data.isi || data.deskripsi || data.teks_hasil || '';
        if (elements.previewContent) {
            elements.previewContent.textContent = textOutput;
        }
    }

    function handleApply() {
        if (!generatedData) return;

        if (generatedData.judul && targetTitleElement) {
            targetTitleElement.value = generatedData.judul;
            targetTitleElement.classList.add('ai-updated-flash');
            setTimeout(() => targetTitleElement.classList.remove('ai-updated-flash'), 1200);
        }

        const textOutput = generatedData.isi || generatedData.deskripsi || generatedData.teks_hasil || '';
        if (textOutput && targetTextElement) {
            targetTextElement.value = textOutput;
            targetTextElement.classList.add('ai-updated-flash');
            setTimeout(() => targetTextElement.classList.remove('ai-updated-flash'), 1200);
        }

        closeModal();
    }
}

// Auto-run if DOM ready or on DOMContentLoaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAiAssistant);
} else {
    initAiAssistant();
}
