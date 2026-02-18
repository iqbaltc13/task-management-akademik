<?php

namespace Database\Seeders;

use App\Models\OwnerCompany;
use Illuminate\Database\Seeder;

class OwnerCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OwnerCompany::create([
            'name' => "UIN Syekh Wasil Kediri",
            'logo' => "https://sia.iainkediri.ac.id/spmb/assets/images/panel/logo-uin-kediri.png",
            'address' => "Jl. Sunan Ampel No.7, Ngronggo, Kec. Kota, Kota Kediri, Jawa Timur ",
            'postal_code' => "64127",
            'city' => "Kota Kediri",
            'country_id' => 104,
            'currency_id' => 42,
            'phone' => "(0354) 689282",
            'web' => 'https://company.com',
            'tax' => 1000, // 10%
            'email' => "info@iainkediri.ac.id",
            'iban' => fake()->iban,
            'swift' => fake()->swiftBicNumber,
            'business_id' => '111111111',
            'tax_id' => '222222222',
            'vat' => '333333333',
        ]);
    }
}
