<?= $this->extend('layouts/admin') ?>
<?= $this->section('content') ?>
<?php
$config = $fieldConfig ?? [];
$choiceOptions = old('choice_options');
if ($choiceOptions === null) {
    $choiceOptions = implode(PHP_EOL, $config['choices'] ?? []);
}
?>
<div class="panel p-4">
    <div class="mb-4">
        <div class="text-secondary small">Formulir</div>
        <h3 class="fw-bold mb-0"><?= esc($formType['nama_form']) ?></h3>
    </div>
    <form method="post" class="row g-4" id="fieldFormBuilder">
        <?= csrf_field() ?>
        <div class="col-md-6">
            <label class="form-label">Nama Isian</label>
            <input type="text" name="label_field" class="form-control" value="<?= old('label_field', $field['label_field'] ?? '') ?>">
        </div>
        <div class="col-md-6">
            <label class="form-label">Nama Sistem</label>
            <input type="text" name="nama_field" class="form-control" value="<?= old('nama_field', $field['nama_field'] ?? '') ?>" placeholder="contoh: nama_lengkap">
            <div class="form-text">Nama ini dipakai sistem. Gunakan huruf kecil dan garis bawah.</div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Jenis Jawaban</label>
            <select name="tipe_field" class="form-select" id="fieldTypeSelect">
                <?php foreach ($fieldTypes as $key => $label): ?>
                    <option value="<?= esc($key) ?>" <?= old('tipe_field', $field['tipe_field'] ?? '') === $key ? 'selected' : '' ?>><?= esc($label) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Urutan</label>
            <input type="number" name="urutan" class="form-control" value="<?= old('urutan', $field['urutan'] ?? 0) ?>">
        </div>
        <div class="col-md-4">
            <label class="form-label">Contoh Isi</label>
            <input type="text" name="placeholder" class="form-control" value="<?= old('placeholder', $field['placeholder'] ?? '') ?>">
        </div>
        <div class="col-12 field-config-panel" data-types="select,radio,checkbox">
            <label class="form-label">Pilihan Jawaban</label>
            <textarea name="choice_options" class="form-control" rows="4" placeholder="Tulis satu pilihan per baris, misalnya: Tetap, Kontrak, Honorer"><?= esc($choiceOptions) ?></textarea>
            <div class="form-text">Pilihan ini akan muncul pada dropdown atau pilihan yang ditampilkan ke pengisi formulir.</div>
        </div>
        <div class="col-md-6 field-config-panel" data-types="text,textarea">
            <label class="form-label">Panjang Minimal</label>
            <input type="number" name="min_length" class="form-control" value="<?= esc(old('min_length', $config['min_length'] ?? '')) ?>" min="0" placeholder="Contoh: 3">
        </div>
        <div class="col-md-6 field-config-panel" data-types="text,textarea">
            <label class="form-label">Panjang Maksimal</label>
            <input type="number" name="max_length" class="form-control" value="<?= esc(old('max_length', $config['max_length'] ?? '')) ?>" min="0" placeholder="Contoh: 150">
        </div>
        <div class="col-md-4 field-config-panel" data-types="number">
            <label class="form-label">Nilai Minimal</label>
            <input type="number" step="any" name="min_number" class="form-control" value="<?= esc(old('min_number', $config['min'] ?? '')) ?>" placeholder="Contoh: 0">
        </div>
        <div class="col-md-4 field-config-panel" data-types="number">
            <label class="form-label">Nilai Maksimal</label>
            <input type="number" step="any" name="max_number" class="form-control" value="<?= esc(old('max_number', $config['max'] ?? '')) ?>" placeholder="Contoh: 100">
        </div>
        <div class="col-md-4 field-config-panel" data-types="number">
            <label class="form-label">Kelipatan Angka</label>
            <input type="number" step="any" name="step_number" class="form-control" value="<?= esc(old('step_number', $config['step'] ?? '')) ?>" placeholder="Contoh: 1 atau 0.5">
        </div>
        <div class="col-md-6 field-config-panel" data-types="date">
            <label class="form-label">Tanggal Paling Awal</label>
            <input type="date" name="min_date" class="form-control" value="<?= esc(old('min_date', $config['min_date'] ?? '')) ?>">
        </div>
        <div class="col-md-6 field-config-panel" data-types="date">
            <label class="form-label">Tanggal Paling Akhir</label>
            <input type="date" name="max_date" class="form-control" value="<?= esc(old('max_date', $config['max_date'] ?? '')) ?>">
        </div>
        <div class="col-md-6 field-config-panel" data-types="file">
            <label class="form-label">Format Berkas yang Diizinkan</label>
            <input type="text" name="allowed_extensions" class="form-control" value="<?= esc(old('allowed_extensions', isset($config['extensions']) ? implode(',', $config['extensions']) : 'pdf,jpg,jpeg,png,doc,docx')) ?>" placeholder="Contoh: pdf,jpg,png">
            <div class="form-text">Pisahkan dengan koma, tanpa titik.</div>
        </div>
        <div class="col-md-6 field-config-panel" data-types="file">
            <label class="form-label">Ukuran Maksimal Berkas (KB)</label>
            <input type="number" name="max_size_kb" class="form-control" value="<?= esc(old('max_size_kb', $config['max_size_kb'] ?? 2048)) ?>" min="1" placeholder="Contoh: 2048">
        </div>
        <div class="col-md-6">
            <label class="form-label">Aturan Isi</label>
            <input type="text" name="validasi" class="form-control" value="<?= old('validasi', $field['validasi'] ?? '') ?>" placeholder="Opsional, untuk aturan tambahan jika diperlukan">
            <div class="form-text">Biasanya tidak perlu diisi. Gunakan hanya jika ingin aturan tambahan khusus.</div>
        </div>
        <div class="col-md-6">
            <label class="form-label">Petunjuk Tambahan</label>
            <input type="text" name="help_text" class="form-control" value="<?= old('help_text', $field['help_text'] ?? '') ?>">
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_required" value="1" <?= old('is_required', $field['is_required'] ?? 0) ? 'checked' : '' ?>>
                <label class="form-check-label">Wajib diisi</label>
            </div>
        </div>
        <div class="col-md-3 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= old('is_active', $field['is_active'] ?? 1) ? 'checked' : '' ?>>
                <label class="form-check-label">Aktif</label>
            </div>
        </div>
        <div class="col-12">
            <div class="admin-form-actions">
                <button class="btn btn-dark rounded-pill px-3">Simpan Isian</button>
                <a href="<?= site_url('admin/forms/' . $formType['id'] . '/fields') ?>" class="btn btn-outline-secondary rounded-pill px-3">Kembali</a>
            </div>
        </div>
    </form>
</div>
<div class="panel p-4 mt-4">
    <div class="d-flex justify-content-between align-items-center gap-3 mb-3 flex-wrap">
        <div>
            <h4 class="fw-bold admin-section-title mb-1">Preview Isian</h4>
            <div class="text-secondary">Gambaran sederhana tampilan isian saat dilihat pengisi formulir.</div>
        </div>
        <span class="badge rounded-pill text-bg-light border" id="previewTypeBadge">Teks Singkat</span>
    </div>
    <div class="border rounded-4 p-4 bg-light-subtle" id="fieldPreviewCard">
        <label class="form-label fw-semibold" id="previewLabel">Nama Isian <span class="text-danger" id="previewRequiredMark" style="display:none">*</span></label>
        <div id="previewControl"></div>
        <div class="form-text mt-2" id="previewHelpText">Petunjuk tambahan akan tampil di sini.</div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const typeSelect = document.getElementById('fieldTypeSelect');
    const panels = document.querySelectorAll('.field-config-panel');
    const labelInput = document.querySelector('input[name="label_field"]');
    const placeholderInput = document.querySelector('input[name="placeholder"]');
    const helpTextInput = document.querySelector('input[name="help_text"]');
    const choiceOptionsInput = document.querySelector('textarea[name="choice_options"]');
    const requiredInput = document.querySelector('input[name="is_required"]');
    const minLengthInput = document.querySelector('input[name="min_length"]');
    const maxLengthInput = document.querySelector('input[name="max_length"]');
    const minNumberInput = document.querySelector('input[name="min_number"]');
    const maxNumberInput = document.querySelector('input[name="max_number"]');
    const stepNumberInput = document.querySelector('input[name="step_number"]');
    const minDateInput = document.querySelector('input[name="min_date"]');
    const maxDateInput = document.querySelector('input[name="max_date"]');
    const allowedExtensionsInput = document.querySelector('input[name="allowed_extensions"]');
    const maxSizeInput = document.querySelector('input[name="max_size_kb"]');
    const previewLabel = document.getElementById('previewLabel');
    const previewControl = document.getElementById('previewControl');
    const previewHelpText = document.getElementById('previewHelpText');
    const previewRequiredMark = document.getElementById('previewRequiredMark');
    const previewTypeBadge = document.getElementById('previewTypeBadge');

    const typeLabels = {
        text: 'Teks Singkat',
        textarea: 'Teks Panjang',
        email: 'Email',
        number: 'Angka',
        date: 'Tanggal',
        select: 'Pilihan Dropdown',
        radio: 'Pilihan Tunggal',
        checkbox: 'Pilihan Jamak',
        file: 'Unggah Berkas'
    };

    if (!typeSelect || !panels.length || !previewControl) return;

    const updatePanels = () => {
        const currentType = typeSelect.value;

        panels.forEach((panel) => {
            const types = (panel.dataset.types || '').split(',').map((item) => item.trim());
            panel.style.display = types.includes(currentType) ? '' : 'none';
        });
    };

    const getChoices = () => {
        return (choiceOptionsInput?.value || '')
            .split(/\r\n|\r|\n/)
            .map((item) => item.trim())
            .filter(Boolean);
    };

    const createInput = (type, extraAttributes = '') => {
        const placeholder = placeholderInput?.value?.trim() || '';
        return `<input type="${type}" class="form-control" placeholder="${placeholder.replace(/"/g, '&quot;')}" ${extraAttributes}>`;
    };

    const updatePreview = () => {
        const currentType = typeSelect.value;
        const label = labelInput?.value?.trim() || 'Nama Isian';
        const helpText = helpTextInput?.value?.trim();
        const choices = getChoices();

        previewLabel.innerHTML = `${label} <span class="text-danger" style="${requiredInput?.checked ? '' : 'display:none'}">*</span>`;
        previewTypeBadge.textContent = typeLabels[currentType] || currentType;
        previewRequiredMark.style.display = requiredInput?.checked ? '' : 'none';
        previewHelpText.textContent = helpText || 'Petunjuk tambahan akan tampil di sini.';

        if (currentType === 'textarea') {
            previewControl.innerHTML = `<textarea class="form-control" rows="4" placeholder="${(placeholderInput?.value || '').replace(/"/g, '&quot;')}"></textarea>`;
            return;
        }

        if (currentType === 'select') {
            const options = choices.length
                ? choices.map((choice) => `<option>${choice}</option>`).join('')
                : '<option>Belum ada pilihan</option>';
            previewControl.innerHTML = `<select class="form-select"><option>Pilih salah satu</option>${options}</select>`;
            return;
        }

        if (currentType === 'radio' || currentType === 'checkbox') {
            const inputType = currentType;
            const options = choices.length ? choices : ['Pilihan 1', 'Pilihan 2'];
            previewControl.innerHTML = `
                <div class="d-grid gap-2">
                    ${options.map((choice, index) => `
                        <label class="form-check border rounded-4 px-3 py-2 bg-white">
                            <input class="form-check-input" type="${inputType}" name="preview_${inputType}" ${inputType === 'radio' && index === 0 ? 'checked' : ''}>
                            <span class="form-check-label">${choice}</span>
                        </label>
                    `).join('')}
                </div>
            `;
            return;
        }

        if (currentType === 'number') {
            const attrs = [
                minNumberInput?.value ? `min="${minNumberInput.value}"` : '',
                maxNumberInput?.value ? `max="${maxNumberInput.value}"` : '',
                stepNumberInput?.value ? `step="${stepNumberInput.value}"` : ''
            ].filter(Boolean).join(' ');
            previewControl.innerHTML = createInput('number', attrs);
            return;
        }

        if (currentType === 'date') {
            const attrs = [
                minDateInput?.value ? `min="${minDateInput.value}"` : '',
                maxDateInput?.value ? `max="${maxDateInput.value}"` : ''
            ].filter(Boolean).join(' ');
            previewControl.innerHTML = createInput('date', attrs);
            return;
        }

        if (currentType === 'file') {
            const extensionText = allowedExtensionsInput?.value?.trim() || 'pdf,jpg,jpeg,png,doc,docx';
            const maxSizeText = maxSizeInput?.value?.trim() || '2048';
            previewControl.innerHTML = `
                <input type="file" class="form-control">
                <div class="form-text mt-2">Format: ${extensionText} | Ukuran maksimal: ${maxSizeText} KB</div>
            `;
            return;
        }

        const attrs = [
            minLengthInput?.value ? `minlength="${minLengthInput.value}"` : '',
            maxLengthInput?.value ? `maxlength="${maxLengthInput.value}"` : ''
        ].filter(Boolean).join(' ');
        previewControl.innerHTML = createInput(currentType === 'email' ? 'email' : 'text', attrs);
    };

    typeSelect.addEventListener('change', updatePanels);
    typeSelect.addEventListener('change', updatePreview);
    [labelInput, placeholderInput, helpTextInput, choiceOptionsInput, requiredInput, minLengthInput, maxLengthInput, minNumberInput, maxNumberInput, stepNumberInput, minDateInput, maxDateInput, allowedExtensionsInput, maxSizeInput]
        .filter(Boolean)
        .forEach((element) => {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        });

    updatePanels();
    updatePreview();
});
</script>
<?= $this->endSection() ?>
