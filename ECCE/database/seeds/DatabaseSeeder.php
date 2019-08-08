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
        	'matricula' => '20170000252',
            'nome' => 'Gabriel',
            'cpf' => '06938297967',
            'email' => 'gabriellucas.fgro@gmail.com',
            'password' => bcrypt('302032')
        ]);
    }
}
