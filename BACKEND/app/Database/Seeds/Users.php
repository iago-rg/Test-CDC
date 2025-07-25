<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Users extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_BR');

        for ($i = 0; $i < 10; $i++) {
            $this->db->table('users')->insert([
                'date_ins' => date('Y-m-d H:i:s'),
                'date_upd' => date('Y-m-d H:i:s'),
                'email' => $faker->email(),
                'password_hash' => password_hash('123', PASSWORD_DEFAULT),
            ]);
        }
    }
}
