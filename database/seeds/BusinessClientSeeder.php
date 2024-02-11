<?php

use App\Models\Address;
use App\Models\BusinessClient;
use Illuminate\Database\Seeder;

class BusinessClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "مصر الجديدة",
            "street" => "وزارة الدفاع",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "وزارة الدفاع",
            "tax_id_number" => "100-642-926",
            "commercial_registeration_number" => "0",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "مدينة نصر",
            "street" => "كيلو 4.5 طريق القاهرة السويس الصحراوي",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "الشركة الوطنية للمقاولات العامة و التوريدات",
            "tax_id_number" => "100-544-975",
            "commercial_registeration_number" => "10600755",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "الدقي",
            "street" => "رقم 3 شارع السلولي الدقي الجيزة",
            "building_no" => "3",
        ]);

        BusinessClient::create([
            "name" => " مصر للمشروعات الميكانيكية والكهربائية ( كهروميكا )",
            "tax_id_number" => "100-366-015",
            "commercial_registeration_number" => "5000184200000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "القاهرة الجديدة",
            "street" => "رقم 8 نموذج 1 مشروع زيزنيا منطقة التجمع الخامس القاهرة",
            "building_no" => "8",
        ]);

        BusinessClient::create([
            "name" => "Madkour EPC",
            "tax_id_number" => "504-510-282",
            "commercial_registeration_number" => "5002074500000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "القاهرة الجديدة",
            "street" => "عقار رقم 8 بيزنس بارك كمبوند زيزينيا القاهرة الجديدة القاهرة مصر ",
            "building_no" => "8",
        ]);

        BusinessClient::create([
            "name" => "Madkour Project",
            "tax_id_number" => "552-472-158",
            "commercial_registeration_number" => "5000754500000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "بولاق",
            "street" => "أبراج النايل سيتي -  البرج الشمالي 2005 ج كورنيش النيل رملة بولاق",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "Orange Misr",
            "tax_id_number" => "205-006-930",
            "commercial_registeration_number" => "5000045720000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "القاهرة الجديدة",
            "street" => "داون تاون شارع التسعين القاهرة مصر صندوق بريد رقم 11",
            "building_no" => "11",
        ]);

        BusinessClient::create([
            "name" => "Etisalat Misr",
            "tax_id_number" => "235-071-579",
            "commercial_registeration_number" => "5000495720000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "القاهرة الجديدة",
            "street" => "رقم 04 ش التسعين كايرو فيستيفال سيتي التجمع الخامس قطعة 12",
            "building_no" => "4",
        ]);

        BusinessClient::create([
            "name" => "Alstom Egypt for transport project",
            "tax_id_number" => "498-894-274",
            "commercial_registeration_number" => "5019454210000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "العباسية",
            "street" => "رقم 351 إمتداد رمسيس اكاديمية الشرطة",
            "building_no" => "351",
        ]);

        BusinessClient::create([
            "name" => "China Electric",
            "tax_id_number" => "518-428-648",
            "commercial_registeration_number" => "5002244500000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "العباسية",
            "street" => "الوحدة التجارية محل رقم 6 بانوراما الجبل الأحمر",
            "building_no" => "6",
        ]);

        BusinessClient::create([
            "name" => "KAM",
            "tax_id_number" => "521-412-420",
            "commercial_registeration_number" => "5049134100000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "ابو رواش",
            "street" => "0",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "الجيزة باور للصناعة",
            "tax_id_number" => "672-823-462",
            "commercial_registeration_number" => "5003392800000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "العباسية",
            "street" => "رقم 10 مساكن القوات المسلحة طريق النصر امام نادي السكة الحديد",
            "building_no" => "10",
        ]);

        BusinessClient::create([
            "name" => "السد العالي للمشروعات الكهربائية-هايدليكو",
            "tax_id_number" => "100-294-731",
            "commercial_registeration_number" => "5000024210000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "العبور",
            "street" => "مدينة العبور المنطقة الصناعية بلوك 27103",
            "building_no" => "27103",
        ]);

        BusinessClient::create([
            "name" => "الشركة الهندسية لجلفنة المعادي",
            "tax_id_number" => "264-663-772",
            "commercial_registeration_number" => "5009752700000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "4",
            "region_city" => "العاشر من رمضان",
            "street" => "منطقة 169 طريق بلبيس - العاشر من رمضان - الشرقية",
            "building_no" => "169",
        ]);

        BusinessClient::create([
            "name" => "المشروعات للصناعات المتطورة",
            "tax_id_number" => "205-150-519",
            "commercial_registeration_number" => "5005082700000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "مصر الجديدة",
            "street" => "رقم 1 شارع الفيوم مصر الجديدة القاهرة",
            "building_no" => "1",
        ]);

        BusinessClient::create([
            "name" => "انيرجيا للصناعات الحديدية",
            "tax_id_number" => "225-649-616",
            "commercial_registeration_number" => "5000452720000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "القاهرة الجديدة",
            "street" => "فيلا 1 قطعة 1 منطقة 10006 المجاورة 10 التجمع الأول",
            "building_no" => "1",
        ]);

        BusinessClient::create([
            "name" => "إم إية إن MAN",
            "tax_id_number" => "200-214-063",
            "commercial_registeration_number" => "5000984100000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "ابو رواش",
            "street" => "القرية الذكية طريق القاهرة  الإسكندرية الصحراوي مبني فودافون",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "فودافون مصر للإتصالات",
            "tax_id_number" => "205-010-725",
            "commercial_registeration_number" => "51000065720000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "شيراتون",
            "street" => "رقم 8 تقسيم مربع 1157 مكرر مساكن شيراتون",
            "building_no" => "8",
        ]);

        BusinessClient::create([
            "name" => "ميجا للإنشاء و الصناعات",
            "tax_id_number" => "611-584-794",
            "commercial_registeration_number" => "5000044210000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "السادس من أكتوبر",
            "street" => "الجي العاشر المحور المركزي قطعة 31 مدينة 6 أكتوبر ج",
            "building_no" => "31",
        ]);

        BusinessClient::create([
            "name" => "يوني باور ايجيبت",
            "tax_id_number" => "200-223-720",
            "commercial_registeration_number" => "5002852700000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "المعادي",
            "street" => "رقم 14 ش 274 المعادي الجديدة الدور الخامس امام القمر الصناعي",
            "building_no" => "14",
        ]);

        BusinessClient::create([
            "name" => "Posco Daewoo Corporation Company",
            "tax_id_number" => "411-895-338",
            "commercial_registeration_number" => "5001324500000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "5",
            "region_city" => "العين السخنة",
            "street" => "رقم 20 مشروع بلاج اند بلاي المنطقة الاقتصادية العين السخنة عتاقة السويس",
            "building_no" => "20",
        ]);

        BusinessClient::create([
            "name" => "ال اس اف مصر للصناعة",
            "tax_id_number" => "589-310-728",
            "commercial_registeration_number" => "5001182700000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "السادس من أكتوبر",
            "street" => "مدينة 6 أكتوبر قطعة 53 ب المنطقة الصناعية الرابعة",
            "building_no" => "53",
        ]);

        BusinessClient::create([
            "name" => "البابطين",
            "tax_id_number" => "204-984-270",
            "commercial_registeration_number" => "5000272720000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "6",
            "region_city" => "دمياط",
            "street" => "دمياط الأعصر الأولي ش شومان ملك محمد عبد الحليم",
            "building_no" => "1",
        ]);

        BusinessClient::create([
            "name" => "الشريف براند شريف عبدالحليم الغريب مصطفي",
            "tax_id_number" => "372-320-805",
            "commercial_registeration_number" => "5001815300101",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "الدقي",
            "street" => "رقم 9 شارع مصدق الدقي الجيزة",
            "building_no" => "9",
        ]);

        BusinessClient::create([
            "name" => "العالمية للهندسة و المقاولات",
            "tax_id_number" => "584-485-662",
            "commercial_registeration_number" => "5018814100000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "الأزبكية",
            "street" => "رقم 31 شارع عماد الدين الأزبكية",
            "building_no" => "31",
        ]);

        BusinessClient::create([
            "name" => "ستال ايجيبت للرافعات و نظم الأوناش و التوريد و التركيب",
            "tax_id_number" => "552-339-539",
            "commercial_registeration_number" => "5010221920011",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "القاهرة الجديدة",
            "street" => "قطعة رقم 20 القطاع الأول التجمع الخامس",
            "building_no" => "20",
        ]);

        BusinessClient::create([
            "name" => "شركة مصر للصيانة صان مصر",
            "tax_id_number" => "204-987-008",
            "commercial_registeration_number" => "5000094420000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "7",
            "region_city" => "السادات",
            "street" => "رقم 11 بجوار بنزينة وطنية منطقة الالفين فدان السادات المنوفية",
            "building_no" => "11",
        ]);

        BusinessClient::create([
            "name" => "الحكيم لتوريدات حديد التسليح و الخردة ( فريد حكيم عبد المسيح )",
            "tax_id_number" => "584-621-566",
            "commercial_registeration_number" => "0",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "الخانكة",
            "street" => "ش الجمال مركز الخانكة القليوبية",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "السماح للتوريدات و براده الحديد",
            "tax_id_number" => "562-405-410",
            "commercial_registeration_number" => "5898519",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "حلوان",
            "street" => "المنطقة الصناعية وادي حوف حلوان ناصية ش 26 يوليو ميدان لبنان المهندسين",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "الشركة المصرية لمنتجات الألومنيوم",
            "tax_id_number" => "100-047-564",
            "commercial_registeration_number" => "5000092700000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "4",
            "region_city" => "ديرب نجم",
            "street" => "صفط زريق ديرب نجم شرقية",
            "building_no" => "30",
        ]);

        BusinessClient::create([
            "name" => "الشهد شهيرة محمد عاطف",
            "tax_id_number" => "306-221-462",
            "commercial_registeration_number" => "5007181910012",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "11",
            "region_city" => "كوم أوشيم",
            "street" => "ق 10 و 14 مرحلة 2 ب مدينة الفتح الصناعية كوم اوشيم",
            "building_no" => "2",
        ]);

        BusinessClient::create([
            "name" => "الفيوم لدرفلة المعادن حمادة زايد",
            "tax_id_number" => "204-890-128",
            "commercial_registeration_number" => "5.00426E+12",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "الزيتون",
            "street" => "2 ش جسر السويس ميدان ابن سدر الزيتون القاهرة",
            "building_no" => "2",
        ]);

        BusinessClient::create([
            "name" => "الكمال للتجارة و المقاولات و الإستثمارات العقارية",
            "tax_id_number" => "200-162-179",
            "commercial_registeration_number" => "5011624100000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "ابو زعبل",
            "street" => "ش صلاح الدين العكرشة ابو زعبل مركز الخانكة قليوبية",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "المتحدة لدرفلة الحديد",
            "tax_id_number" => "313-048-916",
            "commercial_registeration_number" => "5097392700110",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "الخانكة",
            "street" => "كفر عبيان ش الأستاذ عتمان مركز الخانكة القليوبية",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "المدينة المنورة للتجارة و التوريدات خرده المعادن محمد سمير محمد فوزي يوسف",
            "tax_id_number" => "721-149-227",
            "commercial_registeration_number" => "5014651921610",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "السلام",
            "street" => "رقم 25 ش سعد زغلول م السلام قباء",
            "building_no" => "25",
        ]);

        BusinessClient::create([
            "name" => "المؤسسة المصرية لتجارة الخردة",
            "tax_id_number" => "234-870-567",
            "commercial_registeration_number" => "5002461720027",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "4",
            "region_city" => "العاشر من رمضان",
            "street" => "محل 16 عمارة 1 مول المروة العاشر من رمضان",
            "building_no" => "1",
        ]);

        BusinessClient::create([
            "name" => "النور لتجارة و توريد مستلزمات المصانع",
            "tax_id_number" => "548-260-362",
            "commercial_registeration_number" => "5030071920535",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "4",
            "region_city" => "بلبيس",
            "street" => "ابو اشرف الزوامل م بلبيس",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "أيمن السيد محمدي عبد الحليم",
            "tax_id_number" => "511-622-589",
            "commercial_registeration_number" => "5026165520710",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "1",
            "region_city" => "النزهة",
            "street" => "رقم 21 شارع الدكتور احمد زكي النزهة القاهرة",
            "building_no" => "21",
        ]);

        BusinessClient::create([
            "name" => "بلاتينيوم لتجارة و تطوير المعادن",
            "tax_id_number" => "670-355-399",
            "commercial_registeration_number" => "5020751920000",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "9",
            "region_city" => "ميت غمر",
            "street" => "ميت غمر ارض الجزيرة",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "حسين صبري حسين بدوي",
            "tax_id_number" => "300-590-903",
            "commercial_registeration_number" => "5116321720103",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "التبين",
            "street" => "مساكن المرازيق ب 1 م 1 التبين",
            "building_no" => "1",
        ]);

        BusinessClient::create([
            "name" => "سامي جابر عبد الغني إبراهيم",
            "tax_id_number" => "371-562-295",
            "commercial_registeration_number" => "5000291720025",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "8",
            "region_city" => "الواسطي",
            "street" => "شارع فاكوم رقم 28 الوسطي بني سويف",
            "building_no" => "28",
        ]);

        BusinessClient::create([
            "name" => "سامي عيد عبد الظاهر المؤيد بالله للمقاولات",
            "tax_id_number" => "274-263-389",
            "commercial_registeration_number" => "507054100102",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "9",
            "region_city" => "ميت غمر",
            "street" => "ميت غمر ش مراد خلف المستشفي",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "سمير إبراهيم كامل موافي",
            "tax_id_number" => "276-462-734",
            "commercial_registeration_number" => "5089121720103",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "الخانكة",
            "street" => "الخانكة ش سيدي عبد الكريم باطه مركز الخانكة قليوبية",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "سيد عبد العزيز سالم قطب",
            "tax_id_number" => "474-880-982",
            "commercial_registeration_number" => "5077191720109",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "قليوب",
            "street" => "ميت نما مركز قليوب",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "كرم ابراهيم رضوان محمود",
            "tax_id_number" => "100-143-040",
            "commercial_registeration_number" => "5005123102204",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "9",
            "region_city" => "ميت غمر",
            "street" => "ميت غميت غمر ش جمال عبد الناصر ملك معوض ابو العلا",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "مجدي حامد عبد العزيز",
            "tax_id_number" => "300-430-337",
            "commercial_registeration_number" => "5068714100203",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "الخانكة",
            "street" => "القلج حوض العجمي م الخانكة قليوبية",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "name" => "محمود علي عبد الرحمن محمد",
            "tax_id_number" => "411-632-027",
            "commercial_registeration_number" => "5062581720510",
            'address_id' => $address->id,
            'approved' => 1,
        ]);

        $address = Address::create([
            "country_id" => "1",
            "city_id" => "3",
            "region_city" => "الخانكة",
            "street" => "عرب العيادية ش الجمهورية تقسيم التجاريين مركز الخانكة",
            "building_no" => "0",
        ]);

        BusinessClient::create([
            "commercial_registeration_number" => "5097861924806",
            "name" => "مؤسسة الأصدقاء لتجارة المعادن عبد الفتاح شحاتة محمود جبر",
            "tax_id_number" => "312-629-567",
            "commercial_registeration_number" => "5028781911310",
            'address_id' => $address->id,
            'approved' => 1,
        ]);
        $address = Address::create([
            "country_id" => "1",
            "city_id" => "2",
            "region_city" => "إمباية",
            "street" => "ش معهد الابحاث البحري من خلف معهد الابحاث وراق ال 4",
            "building_no" => "0"
        ]);

        BusinessClient::create([

            "name" => "مؤسسة السلام للتوريدات العمومية ( هاني صلاح كامل بكير خليل )",
            "tax_id_number" => "371-814-634",
            'address_id' => $address->id,
            'approved' => 1,
        ]);
    }
}
