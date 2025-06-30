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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar_path')->nullable()->after('email_verified_at');
            $table->text('bio')->nullable()->after('avatar_path');
            $table->string('website')->nullable()->after('bio');
            $table->string('social_facebook')->nullable()->after('website');
            $table->string('social_twitter')->nullable()->after('social_facebook');
            $table->string('social_instagram')->nullable()->after('social_twitter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar_path',
                'bio',
                'website',
                'social_facebook',
                'social_twitter',
                'social_instagram'
            ]);
        });
    }
}; 