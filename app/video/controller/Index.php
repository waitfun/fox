<?php
namespace app\video\controller;
use think\Request;

class Index
{
	protected $request;
	public function __construct(Request $request)
    {
		$this->request = $request;
    }
    
    public function test()
    {
        if ($this->request->isPost())
		{
			$page = input('page');
	        $limit = input('limit');
	        $p = empty($page) ? 1 : $page;          //第几页
	        $num = empty($limit) ? 20 : $limit;      //每页显示的条数

	      	$a = ($p-1) * $num;//其实条数
			$data = db('art_teacher') 
				-> field('XM as name,XB as sex,SJ as phone,EMAIL as email,ZP as avatar')
				-> limit($a,$num) 
				-> select();
			$count = db('art_teacher')
				-> field('ZGH') 
				-> count();
			$page_count = ceil((int)$count/(int)$num);
			$data = ['data'=>$data,'member_count'=>$count,'page_count'=>$page_count,'current_page'=>$p];
			echo json_encode($data,JSON_UNESCAPED_UNICODE);
	    }else{
	    	$data = ['data'=>'你想干啥','status'=>'滚~'];
	    	echo json_encode($data,JSON_UNESCAPED_UNICODE);
	    }
    }
    //轮播图数据
    public function banner()
    {
    	$data = db('video_banner') 
				-> field('pid as id,name as banner_name,url as banner_url,image as banner_image,descr,create_time')
				-> where(['status'=>0]) 
				-> order('create_time desc')
				-> limit(4)
				-> select();
		foreach ($data as $key => $value) 
		{
			$data[$key]['create_time'] = date('Y-m-d H:i:s',$value['create_time']);//转成格式
			$data[$key]['banner_image'] = config('img_path').$value['banner_image'];
		}
        return $data;
    }
    //首页数据
    public function film()
    {
    	$data = db('video_film') 
				-> where(['status'=>0]) 
				-> limit(12)
				-> select();
		
        return $data;
    }
    //某一详细数据
    public function details()
    {
    	$id = $this->request->param('id');
    	$data = db('video_film') 
				-> where(['status'=>0,'id'=>$id]) 
				-> find();
		$data['play_url'] = '//www.82190555.com/video.php?url='.$data['play_url'];//转成格式
		return $data;
    }
    public function search()
    {
    	$keyword = $this->request->param('keyword');
    	$data = db('video_film')
    			-> field('id,name,descr,image,performer,create_time,rank,director')
				-> where(['status'=>0]) 
				-> where('name','like','%'.$keyword.'%')
				-> select();
		if (empty($data)) 
		{
			return ['data'=>'查找的信息不存在','status'=>'no found','code'=>'101'];
		}
		return ['data'=>$data,'status'=>'scuess','code'=>'200'];
    }
   
}
