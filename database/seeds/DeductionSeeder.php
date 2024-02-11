<?php

use App\Models\Deduction;
use Illuminate\Database\Seeder;

class DeductionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // عادى
        Deduction::create([
            'name' => 'ضرائب خصم أرباح تجارية و صناعية',
            'code' => 'D1',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'عمولة تحصيل',
            'code' => 'D2',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'سعر الصرف في حالة الدولار',
            'code' => 'D3',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'دمغة عادية',
            'code' => 'D4',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'دمغة إضافية',
            'code' => 'D5',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'دمغة عقد',
            'code' => 'D6',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'مهن هندسية',
            'code' => 'D7',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'مهن تطبيقية',
            'code' => 'D8',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'مهن زراعية',
            'code' => 'D9',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'مصممي الفنون',
            'code' => 'D10',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'صندوق تحيا مصر (1 % )',
            'code' => 'D11',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'صندوق تحيا مصر ( الكسور )',
            'code' => 'D12',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'سيارات إشراف',
            'code' => 'D13',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'غرامات',
            'code' => 'D14',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'دفعات مقدمة',
            'code' => 'D15',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'تنمية موارد',
            'code' => 'D16',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'دمغات',
            'code' => 'D17',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'سعر شيك',
            'code' => 'D18',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'دمغات متنوعة',
            'code' => 'D19',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'فوائد سالبة',
            'code' => 'D20',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'الإتحاد المصري للتشييد و البناء',
            'code' => 'D21',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'مصروفات عقد',
            'code' => 'D22',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'ضريبة قيمة مضافة',
            'code' => 'D23',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'خصومات',
            'code' => 'D24',
            'deductionType_id' => 1,
        ]);
        Deduction::create([
            'name' => 'تقفيل حساب ( - أو + )',
            'code' => 'D25',
            'deductionType_id' => 1,
        ]);

        // امانات
        Deduction::create([
            'name' => 'تأمينات إجتماعية',
            'code' => 'D26',
            'deductionType_id' => 2,
        ]);
        Deduction::create([
            'name' => 'قوي عاملة',
            'code' => 'D27',
            'deductionType_id' => 2,
        ]);
        Deduction::create([
            'name' => 'تأمين نهائي',
            'code' => 'D28',
            'deductionType_id' => 2,
        ]);
        Deduction::create([
            'name' => 'تأمين أعمال',
            'code' => 'D29',
            'deductionType_id' => 2,
        ]);
        Deduction::create([
            'name' => 'تعلية ضريبة',
            'code' => 'D30',
            'deductionType_id' => 2,
        ]);
        Deduction::create([
            'name' => 'تعليات',
            'code' => 'D31',
            'deductionType_id' => 2,
        ]);
        Deduction::create([
            'name' => 'مصروفات تجهيز الموقع',
            'code' => 'D32',
            'deductionType_id' => 2,
        ]);

        // مواد
        Deduction::create([
            'name' => 'حديد',
            'code' => 'D33',
            'deductionType_id' => 3,
        ]);
        Deduction::create([
            'name' => 'أسمنت',
            'code' => 'D34',
            'deductionType_id' => 3,
        ]);
        Deduction::create([
            'name' => 'خرسانة جاهزة',
            'code' => 'D35',
            'deductionType_id' => 3,
        ]);
        Deduction::create([
            'name' => 'صاج',
            'code' => 'D36',
            'deductionType_id' => 3,
        ]);
        Deduction::create([
            'name' => 'خشب',
            'code' => 'D37',
            'deductionType_id' => 3,
        ]);
        Deduction::create([
            'name' => 'معدات',
            'code' => 'D38',
            'deductionType_id' => 3,
        ]);
    }
}
