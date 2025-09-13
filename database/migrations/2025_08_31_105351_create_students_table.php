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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->nullable()->constrained()->onDelete('set null');

            // Personal Info
            $table->string('nic')->unique();
            $table->string('initial_name');
            $table->string('full_name');
            $table->string('address');
            $table->date('dob');
            $table->enum('gender', ['male', 'female']);
            $table->string('nationality');
            $table->string('religion');
            $table->enum('civil_status', ['married', 'unmarried']);
            $table->string('district');
            $table->string('province');
            $table->string('gn_division');
            $table->string('telephone_number');

            // Educational Info
            $table->string('reg_no')->unique();
            $table->string('batch');
            $table->string('faculty');
            $table->string('department');
            $table->string('course');
            $table->integer('year');

            // Parent/Guardian Info
            $table->string('guardian_name');
            $table->string('guardian_relationship');
            $table->date('guardian_dob');
            $table->string('guardian_mobile');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number');

            // Medical Info
            $table->text('medical_info')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
