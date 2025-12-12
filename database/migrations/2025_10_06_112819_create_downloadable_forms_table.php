<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('downloadable_forms', function (Blueprint $table) {
            $table->id();
            // Enforce unique form titles globally
            $table->string('title')->unique();
            // Enforce unique URLs across all records
            $table->text('url')->unique();
            $table->enum('category', ['rndd_forms', 'moa_forms'])->default('rndd_forms');
            $table->enum('icon_type', ['document', 'spreadsheet', 'book', 'clipboard', 'clock', 'download'])->default('document');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed initial R&DD and MOA forms
        $rnddForms = [
            [
                'title' => 'Research Proposal Form',
                'url' => 'https://docs.google.com/document/d/19mIYizHYIlxamu-P26dLoH-vW6F-CvbT/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monthly Accomplishment Report',
                'url' => 'https://docs.google.com/document/d/1ZZSHK2z6TgJWgkPQXyVJDiuEk48LrAs-/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Quarterly Progress Report',
                'url' => 'https://docs.google.com/document/d/1tw0nt8CNXCWPs5Fm_G6MncP5dBHZEJKN/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monitoring and Evaluation Form',
                'url' => 'https://docs.google.com/document/d/1GT-B3BiOO2JfCUrFG80GaMKSW86yB0pG/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monitoring and Performance Evaluation Form',
                'url' => 'https://docs.google.com/document/d/1QJbgbVS-n4tGblSvD1U1k3fgeb2w9hAv/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Monitoring Minutes',
                'url' => 'https://docs.google.com/spreadsheets/d/1UYp9NdjsW6oN6sRjVq0poQU_VnOv4djw/edit?gid=945965637#gid=945965637',
                'category' => 'rndd_forms',
                'icon_type' => 'spreadsheet',
                'sort_order' => 6,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Terminal Report Form',
                'url' => 'https://docs.google.com/document/d/1EnW1uaU8eG8IOQRoUR6QnFIcOjc8X55r/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 7,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'SETI Scorecard',
                'url' => 'https://docs.google.com/document/d/1XkxD0MkT8034aPOXsq9CtRroghD-SJcU/edit?tab=t.0#heading=h.3vgtho21kx2u',
                'category' => 'rndd_forms',
                'icon_type' => 'clipboard',
                'sort_order' => 8,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'SETI for SDGs Scorecard Guide',
                'url' => 'https://drive.google.com/file/d/13rhFd4jUIz0AwGyGPLfTlz6mjJRcWTF7/view',
                'category' => 'rndd_forms',
                'icon_type' => 'book',
                'sort_order' => 9,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'GAD Assessment Checklist',
                'url' => 'https://docs.google.com/document/d/1xpkPyrU-8607iRytZYfcBxkDe8v0dZtQ/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'clipboard',
                'sort_order' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Special Order Template',
                'url' => 'https://docs.google.com/document/d/1CoQ2jhxVIWuzO3dFbVHuiQwAz6FGjVQi/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 11,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Notice of Engagement Template',
                'url' => 'https://docs.google.com/document/d/1ovn7Xtue-Bw4IbF5Qks0ap4O808smAxe/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'download',
                'sort_order' => 12,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Request Letter for Extension Template',
                'url' => 'https://docs.google.com/document/d/18UiqfkcOOablE2dmTI0Ntb4caX6O7pdU/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'clock',
                'sort_order' => 13,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Updated Workplan Template',
                'url' => 'https://docs.google.com/document/d/1MukrUGF-CLs-akHp4M3atrmG0C-aD1OM/edit?tab=t.0#heading=h.2dw5avqa56uv',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 14,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $moaForms = [
            [
                'title' => 'Review Form for Agreement (RFA)',
                'url' => 'https://docs.google.com/document/d/1EttDrgkqU_r_5m3q-FL7miFJ19USrWsd/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 1,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Routing Slip for Agreements (RSA)',
                'url' => 'https://docs.google.com/document/d/1b0FtrDcBtmzpN4HQX_jlINCcUQJKHcW3/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 2,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'MOA Sample',
                'url' => 'https://docs.google.com/document/d/14zLQNGDZRj9ZlxZqJ19wtM5PGwtr9FcY/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 3,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'MOU Sample',
                'url' => 'https://docs.google.com/document/d/1fkTNmO3IZcSF5dTwf9B6sqOtv1K-JHd2/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 4,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Supplemental MOA Sample',
                'url' => 'https://docs.google.com/document/d/1zKpRnWG6TsE_owxG8ACwqYZEQx_1_E3j/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('downloadable_forms')->insert(array_merge($rnddForms, $moaForms));
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('downloadable_forms');
    }
};
