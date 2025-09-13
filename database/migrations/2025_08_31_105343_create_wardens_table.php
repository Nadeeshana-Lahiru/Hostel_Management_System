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
        Schema::create('wardens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nic')->unique();
            $table->string('initial_name');
            $table->string('full_name');
            $table->string('address');
            $table->date('dob');
            $table->enum('gender', ['male', 'female']);
            $table->string('nationality');
            $table->enum('civil_status', ['married', 'unmarried']);
            $table->string('district');
            $table->string('province');
            $table->string('telephone_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wardens');
    }
};
