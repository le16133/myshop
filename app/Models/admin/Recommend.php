<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
    public $table = 'recommends';
    public $primaryKey = 'id';
 	protected $fillable = ['img','rname','rrname','rurl'];
}
