<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    //操作表
    public $table = 'link';
    public $primaryKey = 'lid';
    protected $fillable = ['lname','lurl','limg'];
}
