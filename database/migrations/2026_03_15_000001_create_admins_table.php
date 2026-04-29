<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('admins', function (Blueprint $table) {
            $table->string('name');
            $table->string('email')->unique();
            $table->integer('age');
            $table->enum('sex', ['male', 'female']);
            $table->string('password');
            $table->rememberToken();  // For auth sessions
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('admins');
    }
};
