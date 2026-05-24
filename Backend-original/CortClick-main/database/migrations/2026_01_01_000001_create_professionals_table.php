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
        Schema::create('professionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('specialty')->comment('Especialidade do profissional');
            $table->text('description')->nullable()->comment('Descrição do profissional');
            $table->string('phone')->nullable()->comment('Telefone de contato');
            $table->time('start_time')->default('09:00')->comment('Hora de início do atendimento');
            $table->time('end_time')->default('18:00')->comment('Hora de fim do atendimento');
            $table->json('available_days')->comment('Dias disponíveis');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professionals');
    }
};
