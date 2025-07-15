<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegionalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $regionals = [
            'Alto Tietê',
            'Interior',
            'ES',
            'SP Interior',
            'SP',
            'SP2',
            'MG',
            'Nacional',
            'SP CAV',
            'RJ',
            'SP2',
            'SP1',
            'NE1',
            'NE2',
            'SUL',
            'Norte',
        ];

        foreach ($regionals as $name) {
            DB::table('regional')->insert([
                'name' => $name
            ]);
        }
    }
}
