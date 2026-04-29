<?php
// database/migrations/xxxx_create_blood_units_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('blood_units', function (Blueprint $table) {
            $table->id();
            $table->string('unit_code')->unique();  // BU-001, BU-002
            $table->foreignId('donation_id')->constrained()->onDelete('cascade');
            $table->string('blood_type');
            $table->decimal('volume', 5, 1);  // 450.0 ml
            $table->date('collection_date');
            $table->date('expiry_date');
            $table->string('location');  // Fridge A, Rack 3
            $table->enum('status', ['available', 'reserved', 'issued', 'expired'])->default('available');
            $table->foreignId('request_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('staff_id')->constrained('staff')->onDelete('restrict');
            $table->timestamps();

            $table->index(['blood_type', 'status']);
            $table->index('expiry_date');
        });
    }

    public function down(): void {
        Schema::dropIfExists('blood_units');
    }
};

