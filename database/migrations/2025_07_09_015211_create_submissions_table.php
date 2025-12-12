<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('authors');
            $table->string('adviser');
            $table->text('abstract');

            // File storage (filesystem)
            $table->string('manuscript_path')->nullable();
            $table->string('manuscript_filename')->nullable();
            $table->unsignedBigInteger('manuscript_size')->nullable();
            $table->string('manuscript_mime')->nullable();

            // Relationships
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('set null');
            $table->foreignId('submitted_by')->constrained('users')->onDelete('restrict');
            $table->unsignedBigInteger('resubmitted_from_id')->nullable()->after('submitted_by');
            $table->foreign('resubmitted_from_id')->references('id')->on('submissions')->onDelete('set null');

            // Submission workflow
            $table->timestamp('submitted_at');
            $table->enum('status', ['pending', 'accepted', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // indexes
            $table->index('title');
            $table->index('program_id');
            $table->index('status');
            $table->index('submitted_by');
            $table->index('resubmitted_from_id');
            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['title', 'abstract']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submissions', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['program_id']);
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['reviewed_by']);
            $table->dropForeign(['resubmitted_from_id']);
        });

        Schema::dropIfExists('submissions');
    }
};
