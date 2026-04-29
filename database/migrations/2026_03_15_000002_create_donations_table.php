<?php
// database/migrations/xxxx_xx_xx_xxxxxx_create_donations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('fullname');
            $table->string('sex');
            $table->integer('age');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->string('bloodtype');
            $table->decimal('weight', 5, 2);
            $table->date('dateoflastdonation')->nullable();
            $table->boolean('disease')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('donations');
    }
};

