<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faculty_form_submissions', function (Blueprint $table) {
            $table->id();

            // Form details
            $table->string('form_type');
            $table->text('note')->nullable();

            // File storage
            $table->string('document_path')->nullable();
            $table->string('document_filename')->nullable();
            $table->unsignedBigInteger('document_size')->nullable();
            $table->string('document_mime')->nullable();

            // Relationships
            $table->foreignId('submitted_by')->constrained('users')->onDelete('restrict');
            $table->unsignedBigInteger('resubmitted_from_id')->nullable()->after('submitted_by');
            $table->foreign('resubmitted_from_id')->references('id')->on('faculty_form_submissions')->onDelete('set null');

            // Submission workflow
            $table->timestamp('submitted_at');
            $table->enum('status', ['pending', 'forwarded', 'accepted', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_remarks')->nullable();
            $table->string('forwarded_to')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('form_type');
            $table->index('status');
            $table->index('submitted_by');
            $table->index('submitted_at');
            $table->index('resubmitted_from_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faculty_form_submissions', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['resubmitted_from_id']);
        });

        Schema::dropIfExists('faculty_form_submissions');
    }
};
