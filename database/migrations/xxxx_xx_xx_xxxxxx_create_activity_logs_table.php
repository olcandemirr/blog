<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Eylemi yapan kullanıcı (silinirse null olur)
            $table->string('log_name')->default('default'); // Log kategorisi (örn: auth, model_updates)
            $table->string('description'); // Eylemin açıklaması
            $table->nullableMorphs('subject'); // Eylemin ilgili olduğu model (örn: Post, User)
            $table->json('properties')->nullable(); // Ekstra bilgi (IP adresi, değişiklikler vb.)
            $table->timestamps();

            $table->index('log_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}; 