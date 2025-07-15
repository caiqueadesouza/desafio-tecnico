<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $specialties = [
            ['name' => 'Cardiologia'],
            ['name' => 'Dermatologia'],
            ['name' => 'Pediatria'],
            ['name' => 'Ortopedia'],
            ['name' => 'Neurologia'],
        ];

        foreach ($specialties as $specialty) {
            DB::table('specialty')->insert([
                'name' => $specialty
            ]);
        }
    }
}
