<?php

use Illuminate\Database\Seeder;

class PaymentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_status')->insert([
            'payment_status' => 'Follow-up'
        ]);
        DB::table('payment_status')->insert([
            'payment_status' => 'Will make payment'
        ]);
        DB::table('payment_status')->insert([
            'payment_status' => 'Paid'
        ]);
        DB::table('payment_status')->insert([
            'payment_status' => 'Not interested'
        ]);
    }
}
