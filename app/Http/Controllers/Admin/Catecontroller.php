<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;
use App\Models\Admin\Cate;
class Catecontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cate_data = Cate::getDatecate();
        // dd($cate_data);
        $n = Cate::count();
        return view('admin.cate.index',['title'=>'分类列表','cate_data'=>$cate_data,'n'=>$n]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 获取显示分类详情
        $cate_data = Cate::getDatecate();
        return view('admin.cate.add',['title'=>'添加分类','cate_data'=>$cate_data]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //获取数据
        $data = $request->except('_token');
        if($data['pid'] == 0) {
            $data['path'] =0;
        } else {
            //拼接id
            $parent_data = Cate::where('tid',$data['pid'])->first();
            //拼接path
            $data['path'] = $parent_data['path'].','.$parent_data['tid'];
                   
        }
        $cate = new Cate();
        $cate -> pid = $data['pid'];
        $cate -> tname = $data['tname'];
        $cate -> path = $data['path'];
        $res = $cate ->save();
        // 判断执行结果
        if ($res) {
            return redirect('/admin/cate')->with('success','添加成功');
        } else {
            return back()->with('error','添加失败');
        }    }

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
         //获取单条数据
        $cate = new Cate();
        $data = $cate::find($id);
        // 获取显示分类详情
        $cate_data = Cate::getDatecate();
        return view('admin.cate.update',['title'=>'修改分类','data'=>$data,'cate_data'=>$cate_data]);
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
         // echo $id;
        //接受修改数据
        $data = $request->except('_token','_method');
        //判断当前添加的分类是否是顶级分类
        if($data['pid'] == 0) {
            $data['path'] = 0;
        } else {
            //拼接id
            $parent_data = Cate::where('tid',$data['pid'])->first();
            //拼接path
            $data['path'] = $parent_data['path'].','.$parent_data['tid'];
        }
        //检测当前列表下面是否有子类
        $cate1 =new Cate();
        $child_data = $cate1->where('pid',$id)->first();
        // dump($child_data);
        // die;
        if (!empty($child_data)) {
            // 当前分类下有子分类不允许修改
            return redirect('/admin/cate')->with('error','当前分类下有子分类,不允许修改');
        }
        // 执行修改
        $cate = cate::find($id);
        $cate -> pid = $data['pid'];
        $cate -> tname = $data['tname'];
        $res = $cate ->save();
        if ($res) {
            return redirect('/admin/cate')->with('success','修改成功');
        } else {
            return back()->with('error','修改失败');
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
         $parent = Cate::where('pid',$id)->first();
         if(!empty($parent)){
            return back()->with('error','当前分类下有子类不能删除');
            exit;
         }
         if(Cate::destroy($id)){
            return redirect('admin/cate')->with('success','删除成功');
         }else{
            return back()->with('error','删除失败');
         }
    }


    

      /**
     * 执行添加子分类
     * @return \Illuminate\Http\Response
     */
    public function getChildcate($id)
    {   
        $cate = new Cate();
        $data = $cate::find($id);
        // 获取显示分类详情
        $cate_data = Cate::getDatecate();
        // dump($data->tid);
        // dump($cate_data);
        return view('admin.cate.childcate',['title'=>'添加子分类','cate_data'=>$cate_data,'data'=>$data]);

    }
}
