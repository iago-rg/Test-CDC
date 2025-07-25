<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Customers extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_BR');

        for ($i = 0; $i < 50; $i++) {
            $this->db->table('customers')->insert([
                'date_ins' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s'),
                'name' => $faker->name(),
                'document_id' => $faker->cpf(false),
                'birth_date' => $faker->date('Y-m-d'),
                'monthly_income' => $faker->randomFloat(2, 0, 5000),
                'balance' => $faker->randomFloat(2, 0, 50000),
            ]);
        }
    }
}
