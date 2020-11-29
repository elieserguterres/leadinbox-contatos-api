<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contato extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'nome',  'email',
        'ramal', 'telefone', 'celular',
        'setor_id',
    ];

    protected $dates = [
        'deleted_at',
    ];

    /**
     * 
     * setor
     * 
     */
    public function setor()
    {

        return $this->belongsTo('App\Models\Setor');
    }
}
