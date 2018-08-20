<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Admin\Lunbo;

class LunboController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // 接收分页条数
        $count = $request -> input('count',3);
        $search_url = $request -> input('url','');
        $params = $request -> all();//以数组的方式接收所有参数
        $data = Lunbo::where('url','like','%'.$search_url.'%')->paginate($count);
        return view('admin.lunbo.index',['title'=>'轮播图列表','data'=>$data,'params'=>$params]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        
        // echo '1111';
        return view('admin.lunbo.create',['title'=>'添加轮播图']);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'pic'=>'required',
            'url'=>'required',
            ],[
            'pic.required'=>'请选择轮播文件',
            'url.required'=>'请输入轮播路径',
            ]);
          //获取输入的值
        $data = $request ->except('_token');
         //创建一张轮播图上传对象
         if($request->hasFile('pic') == true){
            $pic = $request -> file('pic');
            $temp_name = time()+rand(10000,99999);
            // 上传文件后缀
            $hz = $pic -> getClientOriginalExtension();
            $filename = $temp_name.'.'.$hz;
            //执行上传
            $aa = $pic -> move('./ht/lunbo/pic',$filename);
            $data['pic'] = $aa;
        }
        //实例化模型 添加数据
        $lb_data = new Lunbo;  
        if(!isset($data['pic'])){
            $data['pic'] = './ht/lunbo/default_pic/default.';
        }else{
            $lb_data -> pic = $data['pic'];
        }
        $lb_data -> url = $data['url'];
        $res = $lb_data -> save();
         if($res){
            return redirect('/admin/lunbo')->with('success','添加成功'); 
        }else{
            return back()->with('errors','添加失败');
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {   
        $data = lunbo::find($id);

        return view('admin.lunbo.edit',['title'=>'修改轮播图','data'=>$data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {  
         //获取输入的值
        $data = $request ->except('_token');
       //创建轮播图上传对象
         if($request->hasFile('pic') == true){
            $pic = $request -> file('pic');
            $temp_name = time()+rand(10000,99999);
            $hz = $pic -> getClientOriginalExtension();
            $filename = $temp_name.'.'.$hz;
            //执行上传
            $a1 = $pic -> move('./ht/lunbo/',$filename);
            $data['pic'] = $a1;
         }
         //实例化模型 添加数据
        $lb_data = lunbo::find($id);  
        if(!isset($data['pic'])){
            $data['pic'] = './ht/lunbo/default_pic/default.';
        }else{
            $lb_data -> pic = $data['pic'];
        }
        $lb_data -> url = $data['url'];
        $res = $lb_data -> save();
        if($res){
            return redirect('/admin/lunbo')->with('success','修改成功'); 
        }else{
            return back()->with('errors','修改失败');
        }


        // $res = lunbo::find($id)->update(['pic' => $data['pic'],'url' => $data['url'],]);

        // if($res){
        //     return redirect('/admin/lunbo')->with('success','修改成功'); //跳转 并且附带信息
        // }else{
        //     return back()->with('error','修改失败'); //跳转 并且附带信息
        // }  
    }

    


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 实例化数据表
        $lb_data = lunbo::find($id);

        $res = $lb_data->delete();

        if($res){
            return redirect($_SERVER['HTTP_REFERER'])->with('success','删除成功');
        }else{
            return back()->with('error','删除失败');
        }
    }
}
