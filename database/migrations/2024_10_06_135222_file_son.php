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
        Schema::create('file_son',function (Blueprint $table){
            $table->id();
            $table->string('file_son_name',255);
            $table->string('file_son_path',255);
            $table->timestamps();
            $table->unsignedBigInteger('document_sonID'); // Khóa ngoại tham chiếu đến bảng project
            
            // Định nghĩa khóa ngoại tham chiếu đến bảng project
            $table->foreign('document_sonID')->references('id')->on('document_son');
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_son');
    }
};
