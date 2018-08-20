<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Admin\Recommend;
class RecommendController extends Controller
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
        $search_rname = $request -> input('rname','');
        $params = $request -> all();//以数组方式接收所有参数

        $data = recommend::where('rname','like','%'.$search_rname.'%')->paginate($count);

        return view('admin.recommend.index',['title'=>'推荐列表','data'=>$data,'params'=>$params]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.recommend.create',['title'=>'添加推荐']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // // 错误提示信息
        //   $this->validate($request,[
        //     'img'=>'required',
        //     'rname'=>'required',
        //     'rurl'=>'required',
        //     ],[
        //     'img.required'=>'请选择友情logo',
        //     'rname.required'=>'请输入友情名称',
        //     'rurl.required'=>'请输入友情地址',
        //     ]);
         //接收传来的数据
        $recommend_data = $request ->except('_token');
        //创建文件上传对象
        if($request->hasFile('img') == true){
            $img = $request -> file('img');
            $temp_name = time()+rand(10000,99999);
            $hz = $img -> getClientOriginalExtension();
            $filename = $temp_name.'.'.$hz;
            $as = $img -> move('./ht/recommend/',$filename);//执行上传
            $recommend_data['img'] = $as;
        }
        $res = recommend::create($recommend_data);
         if($res){
            return redirect('/admin/recommend')->with('success','添加成功'); //跳转 并且附带信息
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
        $data = recommend::find($id);
        return view('admin.recommend.edit',['title'=>'修改推荐','data'=>$data]);
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
         if($request->hasFile('img') == true){
            $img = $request -> file('img');
            $temp_name = time()+rand(10000,99999);
            $hz = $img -> getClientOriginalExtension();
            $filename = $temp_name.'.'.$hz;
            //执行上传
            $a2 = $img -> move('./ht/recommend/',$filename);
            $data['img'] = $a2;
         }
           //实例化模型 添加数据
        $tj_data = recommend::find($id);  

        if(!isset($data['img'])){
            $data['img'] = './ht/lunbo/default_img/default.';
        }else{
            $tj_data -> img = $data['img'];
        }
        $tj_data -> rurl = $data['rurl'];
        $tj_data -> rname = $data['rname'];
        $tj_data -> rrname = $data['rrname'];
        $res = $tj_data -> save();
        if($res){
            return redirect('/admin/recommend')->with('success','修改成功'); 
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
        $recommend_data = recommend::find($id);
        $res = $recommend_data->delete();
        if($res){
            return redirect($_SERVER['HTTP_REFERER'])->with('success','删除成功');
        }else{
            return back()->with('error','删除失败');
        }
    }
}
