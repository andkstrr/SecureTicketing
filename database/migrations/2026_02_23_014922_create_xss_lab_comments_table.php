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
        Schema::create('xss_lab_comments', function (Blueprint $table) {
            $table->id();
            $table->string('author_name');
            $table->text('content');
            $table->unsignedBigInteger('ticket_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('xss_lab_comments');
    }
};
