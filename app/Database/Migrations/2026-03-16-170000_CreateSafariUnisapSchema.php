<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSafariUnisapSchema extends Migration
{
    public function up()
    {
        $this->createAdmins();
        $this->createFormCategories();
        $this->createFormTypes();
        $this->createFormFields();
        $this->createSubmissions();
        $this->createSubmissionValues();
        $this->createSubmissionFiles();
        $this->createSubmissionLogs();
        $this->createSystemSettings();
        $this->createActivityLogs();
    }

    public function down()
    {
        foreach ([
            'activity_logs',
            'system_settings',
            'submission_logs',
            'submission_files',
            'submission_values',
            'submissions',
            'form_fields',
            'form_types',
            'form_categories',
            'admins',
        ] as $table) {
            $this->forge->dropTable($table, true);
        }
    }

    private function createAdmins(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => 150],
            'email' => ['type' => 'VARCHAR', 'constraint' => 190],
            'username' => ['type' => 'VARCHAR', 'constraint' => 100],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'role' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'admin'],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'last_login_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email', 'uq_admins_email');
        $this->forge->addUniqueKey('username', 'uq_admins_username');
        $this->forge->addKey(['role', 'is_active'], false, false, 'idx_admins_role_active');
        $this->forge->createTable('admins');
    }

    private function createFormCategories(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'nama_kategori' => ['type' => 'VARCHAR', 'constraint' => 150],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 170],
            'urutan' => ['type' => 'INT', 'default' => 0],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_form_categories_slug');
        $this->forge->addKey(['is_active', 'urutan'], false, false, 'idx_form_categories_active_urutan');
        $this->forge->createTable('form_categories');
    }

    private function createFormTypes(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'category_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'nama_form' => ['type' => 'VARCHAR', 'constraint' => 180],
            'slug' => ['type' => 'VARCHAR', 'constraint' => 190],
            'kode_form' => ['type' => 'VARCHAR', 'constraint' => 60],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'icon' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'template_pdf' => ['type' => 'VARCHAR', 'constraint' => 190, 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'urutan' => ['type' => 'INT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('slug', 'uq_form_types_slug');
        $this->forge->addUniqueKey('kode_form', 'uq_form_types_kode_form');
        $this->forge->addKey(['category_id', 'is_active', 'urutan'], false, false, 'idx_form_types_category_active_urutan');
        $this->forge->addForeignKey('category_id', 'form_categories', 'id', 'CASCADE', 'CASCADE', 'fk_form_types_category_id');
        $this->forge->createTable('form_types');
    }

    private function createFormFields(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'form_type_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'nama_field' => ['type' => 'VARCHAR', 'constraint' => 120],
            'label_field' => ['type' => 'VARCHAR', 'constraint' => 180],
            'tipe_field' => ['type' => 'VARCHAR', 'constraint' => 50],
            'placeholder' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'opsi_json' => ['type' => 'LONGTEXT', 'null' => true],
            'is_required' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'urutan' => ['type' => 'INT', 'default' => 0],
            'validasi' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'help_text' => ['type' => 'TEXT', 'null' => true],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['form_type_id', 'is_active', 'urutan'], false, false, 'idx_form_fields_form_active_urutan');
        $this->forge->addKey(['form_type_id', 'nama_field'], false, false, 'idx_form_fields_form_nama_field');
        $this->forge->addForeignKey('form_type_id', 'form_types', 'id', 'CASCADE', 'CASCADE', 'fk_form_fields_form_type_id');
        $this->forge->createTable('form_fields');
    }

    private function createSubmissions(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'form_type_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'kode_pengajuan' => ['type' => 'VARCHAR', 'constraint' => 60],
            'nama_pengaju' => ['type' => 'VARCHAR', 'constraint' => 180],
            'nidn_nip' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'unit_kerja' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'email' => ['type' => 'VARCHAR', 'constraint' => 190],
            'no_hp' => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'tanggal_pengajuan' => ['type' => 'DATETIME'],
            'status' => ['type' => 'VARCHAR', 'constraint' => 50, 'default' => 'masuk'],
            'catatan_admin' => ['type' => 'TEXT', 'null' => true],
            'submitted_ip' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'submitted_user_agent' => ['type' => 'TEXT', 'null' => true],
            'is_archived' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_pengajuan', 'uq_submissions_kode_pengajuan');
        $this->forge->addKey(['form_type_id', 'status', 'tanggal_pengajuan'], false, false, 'idx_submissions_form_status_tanggal');
        $this->forge->addKey(['email', 'kode_pengajuan'], false, false, 'idx_submissions_email_kode');
        $this->forge->addKey(['is_archived', 'tanggal_pengajuan'], false, false, 'idx_submissions_archived_tanggal');
        $this->forge->addForeignKey('form_type_id', 'form_types', 'id', 'CASCADE', 'CASCADE', 'fk_submissions_form_type_id');
        $this->forge->createTable('submissions');
    }

    private function createSubmissionValues(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'submission_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'form_field_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'nama_field' => ['type' => 'VARCHAR', 'constraint' => 120],
            'nilai_text' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'nilai_longtext' => ['type' => 'LONGTEXT', 'null' => true],
            'nilai_date' => ['type' => 'DATE', 'null' => true],
            'nilai_number' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'null' => true],
            'nilai_json' => ['type' => 'LONGTEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['submission_id', 'form_field_id'], false, false, 'idx_submission_values_submission_field');
        $this->forge->addKey(['submission_id', 'nama_field'], false, false, 'idx_submission_values_submission_nama_field');
        $this->forge->addForeignKey('submission_id', 'submissions', 'id', 'CASCADE', 'CASCADE', 'fk_submission_values_submission_id');
        $this->forge->addForeignKey('form_field_id', 'form_fields', 'id', 'SET NULL', 'CASCADE', 'fk_submission_values_form_field_id');
        $this->forge->createTable('submission_values');
    }

    private function createSubmissionFiles(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'submission_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'form_field_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'nama_file_asli' => ['type' => 'VARCHAR', 'constraint' => 255],
            'nama_file_simpan' => ['type' => 'VARCHAR', 'constraint' => 255],
            'path_file' => ['type' => 'VARCHAR', 'constraint' => 255],
            'mime_type' => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'ukuran_file' => ['type' => 'BIGINT', 'unsigned' => true, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['submission_id', 'form_field_id'], false, false, 'idx_submission_files_submission_field');
        $this->forge->addKey('nama_file_simpan', false, false, 'idx_submission_files_nama_simpan');
        $this->forge->addForeignKey('submission_id', 'submissions', 'id', 'CASCADE', 'CASCADE', 'fk_submission_files_submission_id');
        $this->forge->addForeignKey('form_field_id', 'form_fields', 'id', 'SET NULL', 'CASCADE', 'fk_submission_files_form_field_id');
        $this->forge->createTable('submission_files');
    }

    private function createSubmissionLogs(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'submission_id' => ['type' => 'BIGINT', 'unsigned' => true],
            'status_lama' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'status_baru' => ['type' => 'VARCHAR', 'constraint' => 50],
            'catatan' => ['type' => 'TEXT', 'null' => true],
            'changed_by_admin_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'changed_at' => ['type' => 'DATETIME', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['submission_id', 'changed_at'], false, false, 'idx_submission_logs_submission_changed_at');
        $this->forge->addKey('changed_by_admin_id', false, false, 'idx_submission_logs_changed_by_admin_id');
        $this->forge->addForeignKey('submission_id', 'submissions', 'id', 'CASCADE', 'CASCADE', 'fk_submission_logs_submission_id');
        $this->forge->addForeignKey('changed_by_admin_id', 'admins', 'id', 'SET NULL', 'CASCADE', 'fk_submission_logs_changed_by_admin_id');
        $this->forge->createTable('submission_logs');
    }

    private function createSystemSettings(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'setting_key' => ['type' => 'VARCHAR', 'constraint' => 150],
            'setting_value' => ['type' => 'LONGTEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('setting_key', 'uq_system_settings_setting_key');
        $this->forge->createTable('system_settings');
    }

    private function createActivityLogs(): void
    {
        $this->forge->addField([
            'id' => ['type' => 'BIGINT', 'unsigned' => true, 'auto_increment' => true],
            'admin_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'aktivitas' => ['type' => 'VARCHAR', 'constraint' => 255],
            'referensi_tabel' => ['type' => 'VARCHAR', 'constraint' => 100, 'null' => true],
            'referensi_id' => ['type' => 'BIGINT', 'unsigned' => true, 'null' => true],
            'ip_address' => ['type' => 'VARCHAR', 'constraint' => 45, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['admin_id', 'created_at'], false, false, 'idx_activity_logs_admin_created_at');
        $this->forge->addKey(['referensi_tabel', 'referensi_id'], false, false, 'idx_activity_logs_referensi');
        $this->forge->addForeignKey('admin_id', 'admins', 'id', 'SET NULL', 'CASCADE', 'fk_activity_logs_admin_id');
        $this->forge->createTable('activity_logs');
    }
}
