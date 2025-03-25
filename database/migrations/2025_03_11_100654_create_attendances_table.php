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
        Schema::create('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('session');
            $table->string('student');
            $table->enum('status', ['có', 'vắng', 'trễ']);
            $table->foreign('session')->references('id')->on('class_sessions')->onDelete('cascade');
            $table->foreign('student')->references('id')->on('students')->onDelete('cascade');
            $table->primary(['session', 'student']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
