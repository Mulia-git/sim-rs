<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('bpjs_config')) {

         Schema::create('bpjs_config', function (Blueprint $table) {
        $table->id();
        $table->string('base_url');
        $table->string('cons_id');
        $table->string('user_key');
        $table->string('secret_key');
        $table->string('kode_ppk');
        $table->string('nama_ppk');
        $table->boolean('is_active')->default(1);
        $table->timestamps();
    });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpjs_config');
    }
};
