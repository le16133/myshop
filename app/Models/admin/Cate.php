<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Model;
use DB;
class Cate extends Model
{
    public $table = 'gkinds';
    public $primaryKey = 'tid';
    public $timestamps = true;
    public $guarded = [];
   //获取所有分类
    public static function  getDatecate()
		{  
			$cate_data =DB::select("select *,concat(path,',',tid) as paths from gkinds order by paths");  
		     foreach ($cate_data as $key => $value) {
		         //统计path
		         $n = substr_count($value->path,',');
		         //处理tname
		         $cate_data[$key]->tname = str_repeat("!---", $n).$value->tname;
		     }
		      return $cate_data;
		} 

	//递归遍历 所有分类
      public static function getCate($pid =0)
            {
                $cate =Cate::where('pid',$pid)->get();
                $goods_cate = [];
                    foreach ($cate as $key => $value) {   
                         $value->child_cate =self::getCate($value->tid);
                        //查询所有的二级分类
                        // dump($value);
                        $goods_cate[] = $value;
                    }
                return($cate);
            }
}
