<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Transactions extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_BR');

        for ($i = 0; $i < 30; $i++) {
            $this->db->table('transactions')->insert([
                'date_ins' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s'),
                'sender_id' => $faker->numberBetween(1, 30),
                'receiver_id' => $faker->numberBetween(1, 30),
                'amount' => $faker->randomFloat(2, 0, 50000),
            ]);
        }
    }
}
