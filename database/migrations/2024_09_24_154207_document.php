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
        Schema::create('documents',function (Blueprint $table){
            $table->id();
            $table->string('documentName',255);
            $table->timestamps();
            $table->unsignedBigInteger('projectID'); // Khóa ngoại tham chiếu đến bảng project
            
            // Định nghĩa khóa ngoại tham chiếu đến bảng project
            $table->foreign('projectID')->references('id')->on('projects');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
