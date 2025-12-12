<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_activity_logs', function (Blueprint $table) {
            $table->id();

            // Actor Information
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->enum('account_type', ['super_admin', 'admin', 'student', 'faculty'])->index();
            $table->foreignId('program_id')->nullable()->constrained('programs');

            // Action Details (complete list)
            $table
                ->enum('action', [
                    // Authentication (User-side)
                    'registered',
                    'logged_in',
                    'logged_out',
                    'email_verified',
                    'password_changed',
                    'password_reset_requested',
                    'password_reset_successful',

                    // Account Management (User-side)
                    'account_deactivated',
                    'account_reactivated',
                    'profile_updated',
                    'program_changed',

                    // Thesis/Form Submission (User-side)
                    'thesis_submitted',
                    'form_submitted',
                    'thesis_updated',
                    'thesis_deleted',

                    // Admin Actions (Submissions)
                    'thesis_approved',
                    'thesis_declined',
                    'form_approved',
                    'form_rejected',
                    'form_forwarded',
                    'remarks_added',

                    // Admin Actions (Inventory)
                    'inventory_added',
                    'inventory_imported',
                    'inventory_exported',
                    'thesis_archived',

                    // Admin Actions (Users)
                    'user_created',
                    'admin_added',
                    'permissions_updated',

                    // System Actions
                    'backup_created',
                    'system_restored',
                    'backup_restored',
                    'system_reset',

                    // Program Management Actions
                    'program_created',
                    'program_updated',
                    'program_deleted',

                    // Adviser Management Actions
                    'adviser_created',
                    'adviser_updated',
                    'adviser_deleted',

                    // Downloadable Form Actions
                    'downloadable_form_created',
                    'downloadable_form_updated',
                    'downloadable_form_deleted',
                ])
                ->index();

            // Target Entity
            $table->string('target_table', 30)->nullable()->comment('submissions, inventory, users, etc.');
            $table->unsignedBigInteger('target_id')->nullable();

            // Timestamps
            $table->timestamp('performed_at')->useCurrent();

            // Metadata -- information
            $table->json('metadata')->nullable();

            // Optimized Indexes
            $table->index(['user_id', 'performed_at']);
            $table->index(['account_type', 'performed_at']);
            $table->index(['action', 'performed_at']);
            $table->index(['target_table', 'target_id']);
            $table->index(['program_id', 'performed_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_activity_logs');
    }
};
