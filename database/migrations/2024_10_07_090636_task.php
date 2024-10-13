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
        Schema::create('tasks',function (Blueprint $table){
            $table->id();
            $table->string('task_name',255);
            $table->string('note',255);
            $table->date('start');
            $table->date('end');
            $table->integer('status');
            $table->integer('stt');

            $table->unsignedBigInteger('parent_id');
            $table->unsignedBigInteger('userID'); 
            $table->foreign('userID')->references('id')->on('users');
            $table->unsignedBigInteger('projectID'); // Khóa ngoại tham chiếu đến bảng project
            // Định nghĩa khóa ngoại tham chiếu đến bảng project
            $table->foreign('projectID')->references('id')->on('projects');
            $table->timestamps();
        
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');

    }
};
