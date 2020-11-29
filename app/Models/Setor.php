<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setor extends Model
{

    use SoftDeletes;

    protected $table = 'setores';

    protected $fillable = [
        'nome', 'ramal',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * 
     * contatos
     * 
     */
    public function contatos()
    {

        return $this->hasMany('App\Models\Contato');
    }
}
