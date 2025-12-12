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
            $table->id();
            $table->text('first_name');
            $table->text('last_name');
            $table->text('email');
            $table->string('email_hash', 64)->unique()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('account_type', ['super_admin', 'admin', 'student', 'faculty'])->index();
            $table->foreignId('program_id')->nullable()->constrained('programs')->index();
            $table
                ->enum('status', ['active', 'deactivated', 'deleted'])
                ->default('active')
                ->index();
            $table->json('permissions')->nullable();
            $table->timestamps();
            $table->timestamp('deactivated_at')->nullable();
            $table->timestamp('scheduled_for_deletion')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->string('verification_code', 6)->nullable();
            $table->timestamp('verification_code_expires_at')->nullable();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');

        // Need to drop foreign key first before dropping users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['program_id']);
        });

        Schema::dropIfExists('users');
    }
};
