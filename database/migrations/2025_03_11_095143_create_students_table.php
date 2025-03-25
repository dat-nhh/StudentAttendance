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
        Schema::create('students', function (Blueprint $table) {
            $table->string('id');
            $table->string('surname');
            $table->string('forename');
            $table->unsignedBigInteger('class');
            $table->foreign('class')->references('id')->on('my_classes')->onDelete('cascade');
            $table->primary(['id', 'class']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
