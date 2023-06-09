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
        Schema::create('typingspeed', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->length(11)->nullable();
            $table->string('speed');
            $table->string('accuracy');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typingspeed');
    }
};
