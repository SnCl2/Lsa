<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('relatives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->string('phone_number');
            $table->string('relation');
            $table->string('relative_name');
            $table->string('pan_number')->nullable();
            $table->text('address')->nullable();
            $table->foreignId('work_id')->constrained('works')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('relatives');
    }
};
