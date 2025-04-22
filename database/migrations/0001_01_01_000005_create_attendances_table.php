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
            $table->unsignedBigInteger('lesson');
            $table->string('student');
            $table->enum('status', ['có', 'vắng', 'trễ']);
            $table->dateTime('datetime')->nullable();
            $table->string('device')->nullable();
            $table->foreign('lesson')->references('id')->on('lessons')->onDelete('cascade');
            $table->foreign('student')->references('id')->on('students')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['lesson', 'student']);
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
