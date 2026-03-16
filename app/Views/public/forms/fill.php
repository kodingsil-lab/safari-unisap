<?= $this->extend('layouts/public') ?>
<?= $this->section('content') ?>
<section class="py-5">
    <div class="container">
        <div class="card-soft rounded-4 p-4 p-lg-5">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <div class="small text-secondary"><?= esc($form['category_name']) ?></div>
                    <h1 class="fw-bold mb-1"><?= esc($form['name']) ?></h1>
                    <p class="text-secondary mb-0"><?= esc($form['description']) ?></p>
                </div>
                <a href="<?= site_url('formulir/' . $form['slug']) ?>" class="btn btn-outline-dark rounded-pill">Kembali</a>
            </div>

            <?php if ($validation->getErrors()): ?>
                <div class="alert alert-danger">Terdapat kesalahan pada pengisian formulir. Silakan cek kembali field yang ditandai.</div>
            <?php endif; ?>

            <form action="<?= site_url('formulir/' . $form['slug'] . '/submit') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="row g-4">
                    <?php foreach ($fields as $field): ?>
                        <div class="col-md-<?= esc($field['column_width'] ?? 12) ?>">
                            <label class="form-label fw-semibold"><?= esc($field['label']) ?><?php if ($field['is_required']): ?> <span class="text-danger">*</span><?php endif; ?></label>
                            <?php $error = $validation->getError($field['name']); ?>
                            <?php if (in_array($field['field_type'], ['text', 'email', 'date', 'number'], true)): ?>
                                <input
                                    type="<?= esc($field['field_type']) ?>"
                                    name="<?= esc($field['name']) ?>"
                                    value="<?= old($field['name']) ?>"
                                    class="form-control form-control-lg <?= $error ? 'is-invalid' : '' ?>"
                                    placeholder="<?= esc($field['placeholder']) ?>"
                                    <?= ! empty($field['min_length']) ? ' minlength="' . esc((string) $field['min_length']) . '"' : '' ?>
                                    <?= ! empty($field['max_length']) ? ' maxlength="' . esc((string) $field['max_length']) . '"' : '' ?>
                                    <?= array_key_exists('min_number', $field) && $field['min_number'] !== null ? ' min="' . esc((string) $field['min_number']) . '"' : '' ?>
                                    <?= array_key_exists('max_number', $field) && $field['max_number'] !== null ? ' max="' . esc((string) $field['max_number']) . '"' : '' ?>
                                    <?= array_key_exists('step_number', $field) && $field['step_number'] !== null ? ' step="' . esc((string) $field['step_number']) . '"' : '' ?>
                                    <?= ! empty($field['min_date']) ? ' min="' . esc((string) $field['min_date']) . '"' : '' ?>
                                    <?= ! empty($field['max_date']) ? ' max="' . esc((string) $field['max_date']) . '"' : '' ?>
                                >
                            <?php elseif ($field['field_type'] === 'textarea'): ?>
                                <textarea name="<?= esc($field['name']) ?>" rows="4" class="form-control form-control-lg <?= $error ? 'is-invalid' : '' ?>" placeholder="<?= esc($field['placeholder']) ?>" <?= ! empty($field['min_length']) ? ' minlength="' . esc((string) $field['min_length']) . '"' : '' ?> <?= ! empty($field['max_length']) ? ' maxlength="' . esc((string) $field['max_length']) . '"' : '' ?>><?= old($field['name']) ?></textarea>
                            <?php elseif ($field['field_type'] === 'select'): ?>
                                <select name="<?= esc($field['name']) ?>" class="form-select form-select-lg <?= $error ? 'is-invalid' : '' ?>">
                                    <option value="">Pilih salah satu</option>
                                    <?php foreach (field_options($field['options']) as $option): ?>
                                        <option value="<?= esc($option) ?>" <?= old($field['name']) === $option ? 'selected' : '' ?>><?= esc($option) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php elseif ($field['field_type'] === 'radio'): ?>
                                <div class="d-grid gap-2">
                                    <?php foreach (field_options($field['options']) as $option): ?>
                                        <label class="form-check border rounded-4 px-3 py-2">
                                            <input class="form-check-input" type="radio" name="<?= esc($field['name']) ?>" value="<?= esc($option) ?>" <?= old($field['name']) === $option ? 'checked' : '' ?>>
                                            <span class="form-check-label"><?= esc($option) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($field['field_type'] === 'checkbox'): ?>
                                <?php $oldValues = old($field['name']) ?: []; ?>
                                <div class="d-grid gap-2">
                                    <?php foreach (field_options($field['options']) as $option): ?>
                                        <label class="form-check border rounded-4 px-3 py-2">
                                            <input class="form-check-input" type="checkbox" name="<?= esc($field['name']) ?>[]" value="<?= esc($option) ?>" <?= in_array($option, (array) $oldValues, true) ? 'checked' : '' ?>>
                                            <span class="form-check-label"><?= esc($option) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php elseif ($field['field_type'] === 'file'): ?>
                                <input type="file" name="<?= esc($field['name']) ?>" class="form-control form-control-lg <?= $error ? 'is-invalid' : '' ?>" accept="<?= esc($field['accept'] ?? '.pdf,.jpg,.jpeg,.png,.doc,.docx') ?>">
                            <?php endif; ?>
                            <?php if ($field['help_text']): ?><div class="form-text"><?= esc($field['help_text']) ?></div><?php endif; ?>
                            <?php if ($error): ?><div class="invalid-feedback d-block"><?= esc($error) ?></div><?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="pt-4">
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
