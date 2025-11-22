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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // id (PK)

            $table->string('name');                // name
            $table->string('email')->unique();     // email
            $table->text('address')->nullable();   // address (nullable if not always filled)
            $table->string('password');            // password (hashed)

            // role: for customer & admin login
            $table->enum('role', ['customer', 'admin'])->default('customer');

            // security question & answer
            $table->string('security_question');   // e.g. "Apa makanan favoritmu?"
            $table->string('security_answer');     // e.g. "bakso"

            // created_at & updated_at
            $table->timestamps();                  // created_at, updated_at
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
