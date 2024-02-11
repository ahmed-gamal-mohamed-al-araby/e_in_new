<?php

use App\Models\BankTransfer;
use App\Models\Cheque;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PaymentDeduction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::beginTransaction();

        try {

            $payment_method_id = null;
            // Add payment record
            $payment = Payment::create([
                'table' => 'PO', // ['D', 'PO']
                'table_id' => 31,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'cashe', // ['cashe', 'Bank transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => '2021-05-26',
                'value' => '400000',
            ]);

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على أمر شراء CAIRO - N - AM - EEC - 2017 - 8-A1 </a>",
            // ]);

            // ---------------------------------------------------------//

            $cheque = Cheque::create([
                'bank_name' => 'Bank1',
                'cheque_number' => 'Cheque number1',
                'received_date' => '2021-05-25',
                'issue_date' => '2021-05-25',
                'deposit_date' => '2021-05-25',
                'collect_date' => '2021-05-25',
                'bank_id' => 1,
            ]);
            $payment_method_id = $cheque->id;

            // Add payment record
            $payment = Payment::create([
                'table' => 'PO', // ['D', 'PO']
                'table_id' => 35,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'Cheque', // ['cashe', 'Bank transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => '2021-05-26',
                'value' => '400000',
            ]);

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على أمر شراء CAIRO-N-AM-EEC-2017-8 </a>",
            // ]);


            // ---------------------------------------------------------//        

            $bankTransfer = BankTransfer::create([
                'bank_name' => 'Bank2',
                'received_date' => '2020-06-30',
                'issue_date' => '2020-06-30',
                'deposit_date' => '2020-06-30',
                'collect_date' => '2020-06-30',
                'bank_id' => 2,
            ]);
            $payment_method_id = $bankTransfer->id;

            // Add payment record
            $payment = Payment::create([
                'table' => 'PO', // ['D', 'PO']
                'table_id' => 35,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'bank_transfer', // ['cashe', 'Bank transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => '2021-05-26',
                'value' => '35000',
            ]);

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على أمر شراء CAIRO-N-AM-EEC-2017-8 </a>",
            // ]);

            // ---------------------------------------------------------//

            // Add payment record
            $payment = Payment::create([
                'table' => 'PO', // ['D', 'PO']
                'table_id' => 73,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => null,
                'payment_date' => '2021-06-24',
                'value' => '5754',
            ]);

            // Add deductions
            for ($i = 0; $i < 3; $i++) {
                PaymentDeduction::create([
                    'deduction_id' => $i + 1,
                    'value' => 959 * ($i+1),
                    'payment_id' => $payment->id,
                ]);
            }

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على أمر شراء الهيئة الهندسية للقوات المسلحة -إدارة المهندسين العسكريين </a>",
            // ]);
            // ------------------------------------------------------------------------------------------------------------------------------------------------------------//


            $payment_method_id = null;

            // Add payment record
            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => 43,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'cashe', // ['cashe', 'Bank transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => '2021-05-26',
                'value' => '98700',
            ]);

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة 004/2021 </a>",
            // ]);

            // ---------------------------------------------------------//

            $cheque = Cheque::create([
                'bank_name' => 'Bank1',
                'cheque_number' => 'Cheque number1',
                'received_date' => '2021-05-25',
                'issue_date' => '2021-05-25',
                'deposit_date' => '2021-05-25',
                'collect_date' => '2021-05-25',
                'bank_id' => 1,
            ]);
            $payment_method_id = $cheque->id;

            // Add payment record
            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => 44,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'Cheque', // ['cashe', 'Bank transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => '2021-05-26',
                'value' => '5800',
            ]);

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة 045/2021 </a>",
            // ]);


            // ---------------------------------------------------------//        

            $bankTransfer = BankTransfer::create([
                'bank_name' => 'Bank2',
                'received_date' => '2020-06-30',
                'issue_date' => '2020-06-30',
                'deposit_date' => '2020-06-30',
                'collect_date' => '2020-06-30',
                'bank_id' => 2,
            ]);
            $payment_method_id = $bankTransfer->id;

            // Add payment record
            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => 33,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'bank_transfer', // ['cashe', 'Bank transfer', 'Cheque', 'Deduction']
                'payment_method_id' => $payment_method_id,
                'payment_date' => '2021-05-26',
                'value' => '6300',
            ]);

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة B 30% 2019 </a>",
            // ]);

            // ---------------------------------------------------------//

            // Add payment record
            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => 38,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => null,
                'payment_date' => '2021-05-30',
                'value' => '36',
            ]);

            // Add deductions
            for ($i = 0; $i < 3; $i++) {
                PaymentDeduction::create([
                    'deduction_id' => $i + 3,
                    'value' => 6 * ($i+1),
                    'payment_id' => $payment->id,
                ]);
            }

             // Add payment record
             $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => 38,
                'client_type' => 'b',
                'client_id' => 2,
                'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => null,
                'payment_date' => '2021-05-26',
                'value' => '36',
            ]);

            // Add deductions
            for ($i = 0; $i < 3; $i++) {
                PaymentDeduction::create([
                    'deduction_id' => $i + 3,
                    'value' => 6 * ($i+1),
                    'payment_id' => $payment->id,
                ]);
            }

            $payment = Payment::create([
                'table' => 'D', // ['D', 'PO']
                'table_id' => 38,
                'client_type' => 'b',
                'client_id' => 1,
                'payment_method' => 'Deduction', // ['cashe', 'bank_transfer', 'Cheque', 'Deduction']
                'payment_method_id' => null,
                'payment_date' => '2021-06-01',
                'value' => '36',
            ]);

            // Add deductions
            for ($i = 0; $i < 3; $i++) {
                PaymentDeduction::create([
                    'deduction_id' => $i + 3,
                    'value' => 6 * ($i+1),
                    'payment_id' => $payment->id,
                ]);
            }

            // Notification part
            // $url = route('payment.show',  $payment->id);

            // $notification = Notification::create([
            //     'content' => '',
            //     'user_id' => auth()->user()->id,
            //     'type' => 'a',
            //     'table_name' => 'payments',
            //     'record_id' => $payment->id,
            // ]);

            // // set content
            // $notification->update([
            //     'content' => auth()->user()->username . ' انشأ دفع ' . "<br><a class=\"btn btn-sm btn-info m-1\" target=\"_blank\" href=\"$url?n_id=$notification->id\">دفع على وثيقة 007/2021 </a>",
            // ]);

            DB::commit();

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
        }
    }
}
