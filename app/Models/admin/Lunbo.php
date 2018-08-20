<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Lunbo extends Model
{
  	public $table = 'lunbo';
    public $primaryKey = 'cid';
 	protected $fillable = ['pic','url'];
}
