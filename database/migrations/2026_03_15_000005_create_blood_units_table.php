<?php
// database/migrations/xxxx_create_blood_units_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('blood_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');
            $table->string('blood_type');
            $table->foreignId('request_id')->nullable()->constrained('requests')->nullOnDelete();
            $table->decimal('volume')->default(450);  // 450.0 ml
            $table->date('expiry_date');
            $table->timestamps();

            $table->index(['blood_type']);
            $table->index('expiry_date');
        });
    }

    public function down(): void {
        Schema::dropIfExists('blood_units');
    }
};

