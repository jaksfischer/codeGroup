<?php

namespace Database\Seeders;

use App\Http\Controllers\PlayersController;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlayersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rand = rand(15, 30);
        for($i = 1; $i <= $rand; $i++) {
            DB::table('players')->insert([
                'id'            => $i,
                'name'          => 'Player'.$i,
                'level'         => rand(1, 5),
                'isGoalkeeper'  => rand(0, 1),
                'presence'      => rand(0,1),
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s')
            ]);
        }
    }
}
