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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            // This links the admin profile to their login account in the 'users' table.
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('initial_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('nic')->unique()->nullable();
            $table->string('email')->unique();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->enum('civil_status', ['married', 'unmarried'])->nullable();
            $table->string('district')->nullable();
            $table->string('province')->nullable();
            $table->string('telephone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
