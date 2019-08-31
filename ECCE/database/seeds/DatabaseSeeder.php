<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('funcionarios')->insert([
            'nome' => 'Root',
            'matricula' => 'root_ecce',
            'password' => bcrypt('36963ecce')
        ]);

        DB::table('funcionarios')->insert([
            'nome' => 'Secretaria 1',
            'matricula' => 'secretaria_1',
            'password' => bcrypt('ecce0101')
        ]);

        DB::table('funcionarios')->insert([
            'nome' => 'Secretaria 2',
            'matricula' => 'secretaria_2',
            'password' => bcrypt('ecce0202')
        ]);

        DB::table('funcionarios')->insert([
            'nome' => 'Secretaria 3',
            'matricula' => 'secretaria_3',
            'password' => bcrypt('ecce0202')
        ]);
    }
}
