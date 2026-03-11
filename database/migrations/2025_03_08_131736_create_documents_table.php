<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_id');
            $table->string('document_name');
            $table->date('date_of_issue');
            $table->string('image'); // Assuming this will store the file path
            $table->timestamps();
            $table->foreign('work_id')->references('id')->on('works')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
