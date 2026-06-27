<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifikasi ENUM untuk menambahkan 'headmaster'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'parent', 'headmaster') NOT NULL DEFAULT 'parent'");
    }

    public function down(): void
    {
        // Kembalikan ENUM tanpa 'headmaster'
        // Pastikan tidak ada user dengan role 'headmaster' sebelum rollback
        DB::statement("UPDATE users SET role = 'admin' WHERE role = 'headmaster'");
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'parent') NOT NULL DEFAULT 'parent'");
    }
};
