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
		}
        return $data;
    }
}
