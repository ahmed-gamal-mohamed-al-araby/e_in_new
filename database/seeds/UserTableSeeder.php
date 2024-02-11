<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $super_admin = User::create([
            'name' => 'web.team',
            'username' => 'Web Team',
            'email' => 'web.team@eecegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);

        $super_admin->attachRole('super_admin');

        $admin = User::create([
            'name' => 'kareem.saleh',
            'username' => 'Kareem Saleh',
            'email' => 'kareem.saleh@eecegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);

        $admin->attachRole('admin');

        $moderator1  = User::create([
            'name' => 'paul.youssif',
            'username' => 'Paul Youssif',
            'email' => 'paul85anton@gmail.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $moderator1->attachRole('moderator');

        $moderator2  = User::create([
            'name' => 'waleed.seif',
            'username' => 'Waleed Seif',
            'email' => 'waleed.seif@eecegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $moderator2->attachRole('moderator');

        $normal  = User::create([
            'name' => 'ahmed.adel',
            'username' => 'Ahmed Adel',
            'email' => 'ahmedadel199623@gmail.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $normal->attachRole('normal');

        $show_invoice2  = User::create([
            'name' => 'amira.anwar',
            'username' => 'Amira Anwar',
            'email' => 'amira.anwar@eecegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $show_invoice2->attachRole('admin');

        $show_invoice3  = User::create([
            'name' => 'dina.nayl',
            'username' => 'Dina Nayl',
            'email' => 'dina.nayl@eecegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $show_invoice3->attachRole('show_invoices');

        $show_invoice4  = User::create([
            'name' => 'rasha.samir',
            'username' => 'Rasha Samir',
            'email' => 'rasha.samir@roxegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $show_invoice4->attachRole('show_invoices');

        $taxes_report_show  = User::create([
            'name' => 'nermine.shawky',
            'username' => 'Nermine Shawky',
            'email' => 'nermine.shawky@eecegypt.com',
            'password' => bcrypt('123456'),
            'company_id' => 1
        ]);
        $taxes_report_show->attachRole('taxes_report_show');
    }
}
