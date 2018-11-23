<?php
namespace app\video\controller;
use think\Request;
use think\Db;

class Index
{
	protected $request;
	public function __construct(Request $request)
    {
		$this->request = $request;
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
    //推荐数据，随机12条，评分大于8的
    public function film()
    {
    	$sql = "SELECT t1.id,t1.image_url,t1.name,t1.performer,t1.score FROM video_film AS t1 
				JOIN 
				(SELECT ROUND(RAND() * (SELECT MAX(id) FROM video_film)) AS id) AS t2 
				WHERE t1.id >= t2.id and score>=8 ORDER BY t1.id ASC LIMIT 12";
		$data = Db::query($sql);
		
        return $data;
    }
    //某一详细数据
    public function details()
    {
    	if ($this->request->isPost())
		{
	    	$id = $this->request->param('id');
	    	$data = db('video_film') 
					-> where(['status'=>0,'id'=>$id]) 
					-> find();
			$data['play_url'] = 'http://www.82190555.com/video.php?url='.$data['play_url'];//转成格式
			return $data;
		}else{
	    	$data = ['data'=>'你想干啥','status'=>'滚~'];
	    	echo json_encode($data,JSON_UNESCAPED_UNICODE);
	    }
    }
    //搜索
    public function search()
    {
    	if ($this->request->isPost())
		{
	    	$keyword = $this->request->param('keyword');
	    	
	    	$data = db('video_film')
	    			-> field('id,name,descr,image_url,performer,create_time,score,director')
					-> where(['status'=>0]) 
					-> where('name','like','%'.$keyword.'%')
					-> select();
			if (empty($data)) 
			{
				return ['data'=>'查找的信息不存在','status'=>'no found','code'=>'101'];
			}
			return ['data'=>$data,'status'=>'scuess','code'=>'200'];
	    }else{
	    	$data = ['data'=>'你想干啥','status'=>'滚~'];
	    	echo json_encode($data,JSON_UNESCAPED_UNICODE);
	    }
    }
    //分类
    public function assortment()
    {
    	if ($this->request->isPost())
		{
	    	$keyword = $this->request->param('keyword');
	    	$page =  $this->request->param('page');
		    $limit =  $this->request->param('limit');
		    $p = empty($page) ? 1 : $page;          //第几页
		    $num = empty($limit) ? 20 : $limit;      //每页显示的条数

		    $a = ($p-1) * $num;//其实条数
	    	$data = db('video_film')
	    			-> field('id,name,descr,image_url,performer,create_time,score,director')
					-> where(['status'=>0]) 
					-> where('style_name','like','%'.$keyword.'%')
					-> limit($a,$num) 
					-> select();
			$count = db('video_film')
					-> field('id') 
					-> where('style_name','like','%'.$keyword.'%')
					-> count();
			$page_count = ceil((int)$count/(int)$num);
			if (empty($data)) 
			{
				return ['data'=>'查找的信息不存在','status'=>'no found','code'=>'101'];
			}
			
			return ['data'=>$data,'member_count'=>$count,'page_count'=>$page_count,'current_page'=>$p,'status'=>'scuess','code'=>'200'];
		}else{
	    	$data = ['data'=>'你想干啥','status'=>'滚~'];
	    	echo json_encode($data,JSON_UNESCAPED_UNICODE);
	    }
    }
   
}
