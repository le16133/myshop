<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Admin\link;
class LinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {  
        $count = $request -> input('count',3);
        $search_lname = $request -> input('lname','');
        $params = $request ->all();

        // $count = $request -> input('count',3);
       //获取网站配置信息
        $data = link::where('lname','like','%'.$search_lname.'%')->paginate($count);
        // dump($data);
        return view('admin.link.index',['title'=>'友情链接显示','data'=>$data,'params'=>$params]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.link.create',['title'=>'友情链接添加']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 错误提示信息
          $this->validate($request,[
            'limg'=>'required',
            'lname'=>'required',
            'lurl'=>'required',
            ],[
            'limg.required'=>'请选择友情logo',
            'lname.required'=>'请输入友情名称',
            'lurl.required'=>'请输入友情地址',
            ]);
         //接收传来的数据
        $link_data = $request ->except('_token');
        //创建文件上传对象
        if($request->hasFile('limg') == true){
            $limg = $request -> file('limg');
            $temp_name = time()+rand(10000,99999);
            $hz = $limg -> getClientOriginalExtension();
            $filename = $temp_name.'.'.$hz;
            $as = $limg -> move('./Admins/Uploads/',$filename);//执行上传
            $link_data['limg'] = $as;
        }
        $res = Link::create($link_data);
         if($res){
            return redirect('/admin/link')->with('success','添加成功'); //跳转 并且附带信息
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
        $data = link::find($id);

        return view('admin.link.edit',['title'=>'修改链接','data'=>$data]);
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
         if($request->hasFile('limg') == true){
            $limg = $request -> file('limg');
            $temp_name = time()+rand(10000,99999);
            $hz = $limg -> getClientOriginalExtension();
            $filename = $temp_name.'.'.$hz;
            //执行上传
            $a1 = $limg -> move('./ht/link/',$filename);
            $data['limg'] = $a1;
         }
         //实例化模型 添加数据
        $yq_data = link::find($id);  
        if(!isset($data['limg'])){
            $data['limg'] = './ht/lunbo/default_limg/default.';
        }else{
            $yq_data -> limg = $data['limg'];
        }
        $yq_data -> lurl = $data['lurl'];
        $yq_data -> lname = $data['lname'];
        $res = $yq_data -> save();
        if($res){
            return redirect('/admin/link')->with('success','修改成功'); 
        }else{
            return back()->with('errors','修改失败');
        }

       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $link_data = link::find($id);
        $res = $link_data->delete();
        if($res){
            return redirect($_SERVER['HTTP_REFERER'])->with('success','删除成功');
        }else{
            return back()->with('error','删除失败');
        }
    }
}
