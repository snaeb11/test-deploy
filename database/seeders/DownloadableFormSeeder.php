<?php

namespace Database\Seeders;

use App\Models\DownloadableForm;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DownloadableFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing forms
        DownloadableForm::truncate();

        // R&DD Forms
        $rnddForms = [
            [
                'title' => 'Research Proposal Form',
                'url' => 'https://docs.google.com/document/d/19mIYizHYIlxamu-P26dLoH-vW6F-CvbT/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Monthly Accomplishment Report',
                'url' => 'https://docs.google.com/document/d/1ZZSHK2z6TgJWgkPQXyVJDiuEk48LrAs-/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Quarterly Progress Report',
                'url' => 'https://docs.google.com/document/d/1tw0nt8CNXCWPs5Fm_G6MncP5dBHZEJKN/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'Monitoring and Evaluation Form',
                'url' => 'https://docs.google.com/document/d/1GT-B3BiOO2JfCUrFG80GaMKSW86yB0pG/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Monitoring and Performance Evaluation Form',
                'url' => 'https://docs.google.com/document/d/1QJbgbVS-n4tGblSvD1U1k3fgeb2w9hAv/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'title' => 'Monitoring Minutes',
                'url' => 'https://docs.google.com/spreadsheets/d/1UYp9NdjsW6oN6sRjVq0poQU_VnOv4djw/edit?gid=945965637#gid=945965637',
                'category' => 'rndd_forms',
                'icon_type' => 'spreadsheet',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'title' => 'Terminal Report Form',
                'url' => 'https://docs.google.com/document/d/1EnW1uaU8eG8IOQRoUR6QnFIcOjc8X55r/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'title' => 'SETI Scorecard',
                'url' => 'https://docs.google.com/document/d/1XkxD0MkT8034aPOXsq9CtRroghD-SJcU/edit?tab=t.0#heading=h.3vgtho21kx2u',
                'category' => 'rndd_forms',
                'icon_type' => 'clipboard',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'title' => 'SETI for SDGs Scorecard Guide',
                'url' => 'https://drive.google.com/file/d/13rhFd4jUIz0AwGyGPLfTlz6mjJRcWTF7/view',
                'category' => 'rndd_forms',
                'icon_type' => 'book',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'title' => 'GAD Assessment Checklist',
                'url' => 'https://docs.google.com/document/d/1xpkPyrU-8607iRytZYfcBxkDe8v0dZtQ/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'clipboard',
                'sort_order' => 10,
                'is_active' => true,
            ],
            [
                'title' => 'Special Order Template',
                'url' => 'https://docs.google.com/document/d/1CoQ2jhxVIWuzO3dFbVHuiQwAz6FGjVQi/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 11,
                'is_active' => true,
            ],
            [
                'title' => 'Notice of Engagement Template',
                'url' => 'https://docs.google.com/document/d/1ovn7Xtue-Bw4IbF5Qks0ap4O808smAxe/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'download',
                'sort_order' => 12,
                'is_active' => true,
            ],
            [
                'title' => 'Request Letter for Extension Template',
                'url' => 'https://docs.google.com/document/d/18UiqfkcOOablE2dmTI0Ntb4caX6O7pdU/edit?tab=t.0',
                'category' => 'rndd_forms',
                'icon_type' => 'clock',
                'sort_order' => 13,
                'is_active' => true,
            ],
            [
                'title' => 'Updated Workplan Template',
                'url' => 'https://docs.google.com/document/d/1MukrUGF-CLs-akHp4M3atrmG0C-aD1OM/edit?tab=t.0#heading=h.2dw5avqa56uv',
                'category' => 'rndd_forms',
                'icon_type' => 'document',
                'sort_order' => 14,
                'is_active' => true,
            ],
        ];

        // MOA Forms
        $moaForms = [
            [
                'title' => 'Review Form for Agreement (RFA)',
                'url' => 'https://docs.google.com/document/d/1EttDrgkqU_r_5m3q-FL7miFJ19USrWsd/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Routing Slip for Agreements (RSA)',
                'url' => 'https://docs.google.com/document/d/1b0FtrDcBtmzpN4HQX_jlINCcUQJKHcW3/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'MOA Sample',
                'url' => 'https://docs.google.com/document/d/14zLQNGDZRj9ZlxZqJ19wtM5PGwtr9FcY/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'title' => 'MOU Sample',
                'url' => 'https://docs.google.com/document/d/1fkTNmO3IZcSF5dTwf9B6sqOtv1K-JHd2/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'title' => 'Supplemental MOA Sample',
                'url' => 'https://docs.google.com/document/d/1zKpRnWG6TsE_owxG8ACwqYZEQx_1_E3j/edit?tab=t.0',
                'category' => 'moa_forms',
                'icon_type' => 'document',
                'sort_order' => 5,
                'is_active' => true,
            ],
        ];

        // Insert all forms
        foreach (array_merge($rnddForms, $moaForms) as $form) {
            DownloadableForm::create($form);
        }
    }
}
