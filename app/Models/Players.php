<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Players extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'isGoalkeeper',
        'presence'
    ];

/*    public function createPlayer(array $data)
    {
        try {
            $create = Players::create([
                'name'          => $data['name'],
                'level'         => $data['level'],
                'isGoalkeeper'  => $data['isGoalkeeper'],
                'presence'      => $data['presence']
            ]);

            if($create->save()) {
                return "Player added with success!";
            } else {
                throw new Exception();
            }

        } catch (Exception $e) {
            return "An error has occurred: " . $e->getMessage();
        }
    }*/
}
