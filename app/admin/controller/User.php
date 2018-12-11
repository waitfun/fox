<?php
namespace app\admin\controller;
use think\Cache;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;

class User extends Common
{
	public function __construct()
    {
    	parent::__construct();
	}
	public function get_user()
	{
		if (null != $this ->cache) 
		{
			return ['data'=>$this->cache,'code'=>'200'];
		}else{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}

	}
	public function change_pswd()
	{

	}
}