<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_requests_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('requester_type', ['hospital', 'patient']);
            $table->string('patient_name');
            $table->integer('patient_age');
            $table->string('patient_sex');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('required_blood_type');
            $table->integer('units');
            $table->enum('urgency', ['normal', 'emergency']);
            $table->datetime('request_datetime');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('requests');
    }
};

