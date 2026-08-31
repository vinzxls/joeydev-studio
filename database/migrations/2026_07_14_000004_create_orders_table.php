<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique(); // mis: JD-2026-A7K3XQ9B
            $table->string('client_name');
            $table->string('client_wa');
            $table->string('client_email');
            $table->foreignId('subject_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description');
            $table->date('deadline');

            // Snapshot harga saat order dibuat (agar tidak berubah kalau admin ubah konfigurasi)
            $table->unsignedBigInteger('base_price')->nullable();
            $table->string('scale_label')->nullable();
            $table->decimal('scale_value', 4, 2)->nullable();
            $table->string('complexity_label')->nullable();
            $table->decimal('complexity_value', 4, 2)->nullable();
            $table->string('urgency_label')->nullable();
            $table->decimal('urgency_value', 4, 2)->nullable();
            $table->unsignedBigInteger('addons_total')->default(0);
            $table->unsignedBigInteger('final_price')->nullable(); // null = menunggu penawaran admin (subject custom)

            $table->enum('status', ['menunggu_konfirmasi', 'dikerjakan', 'review_revisi', 'selesai', 'dibatalkan'])
                ->default('menunggu_konfirmasi');
            $table->enum('payment_status', ['belum_bayar', 'dp', 'lunas'])->default('belum_bayar');
            $table->string('payment_proof_path')->nullable();
            $table->text('admin_note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
