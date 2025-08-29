<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BankAccount;

class BankAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bankAccounts = [
            [
                'bank_name' => 'Bank Central Asia (BCA)',
                'account_number' => '1234567890',
                'account_holder' => 'EventTick Indonesia',
                'account_type' => 'savings',
                'is_active' => true,
                'description' => 'Rekening utama untuk pembayaran event'
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_holder' => 'EventTick Indonesia',
                'account_type' => 'savings',
                'is_active' => true,
                'description' => 'Rekening alternatif untuk pembayaran event'
            ],
            [
                'bank_name' => 'Bank Rakyat Indonesia (BRI)',
                'account_number' => '1122334455',
                'account_holder' => 'EventTick Indonesia',
                'account_type' => 'current',
                'is_active' => true,
                'description' => 'Rekening giro untuk pembayaran event'
            ]
        ];

        foreach ($bankAccounts as $bankAccount) {
            BankAccount::create($bankAccount);
        }
    }
}
