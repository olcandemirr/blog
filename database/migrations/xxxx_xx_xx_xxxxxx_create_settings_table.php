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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Ayar anahtarı (örn: site_title, meta_description)
            $table->text('value')->nullable(); // Ayar değeri
            $table->string('group')->default('general'); // Ayar grubu (general, seo, social, analytics...)
            $table->string('type')->default('text'); // Input tipi (text, textarea, boolean, file...)
            $table->text('options')->nullable(); // Option'lar (select, radio gibi input tipleri için)
            $table->timestamps();
            
            // Hızlı erişim için indeksler
            $table->index('group');
            $table->index(['key', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}; 