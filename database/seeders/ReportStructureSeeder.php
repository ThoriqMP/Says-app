<?php

namespace Database\Seeders;

use App\Models\ReportCategory;
use App\Models\ReportSubject;
use Illuminate\Database\Seeder;

class ReportStructureSeeder extends Seeder
{
    public function run(): void
    {
        $structures = [
            'Akademik' => [
                'Bahasa Indonesia',
                'Bahasa Inggris',
                'Matematika',
                'IPA',
                'IPS',
                'PKN',
            ],
            'Diniyah' => [
                'Fiqh',
                'Aqidah',
                'Akhlak',
                'Tarikh',
                'Bahasa Arab',
            ],
            'Praktek Ibadah' => [
                'Wudhu',
                'Shalat',
                'Dzikir',
                'Doa Harian',
            ],
            'Probing' => [], // Probing is dynamic activity-based
        ];

        foreach ($structures as $categoryName => $subjects) {
            $category = ReportCategory::create(['name' => $categoryName]);

            foreach ($subjects as $subjectName) {
                ReportSubject::create([
                    'report_category_id' => $category->id,
                    'name' => $subjectName,
                ]);
            }
        }
    }
}
