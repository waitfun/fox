<?php
namespace app\admin\controller;
use think\Cache;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;

class Setting extends Common
{
	public function __construct()
    {
    	parent::__construct();
	}
	//更改密码
	public function change_password()
	{
		$data        = $this->request->param('data');
		if (!is_array($data)) 
		{
			return ['data'=>'数据格式为数组','code'=>101];
		}
		$old_password        = isset($data['old_password'])?$data['old_password']:null;
		$new_password        = isset($data['new_password'])?$data['new_password']:null;
		$new_again_password  = isset($data['new_again_password'])?$data['new_again_password']:null;
		if (empty($old_password)||empty($new_password)||empty($new_again_password)) 
		{
			return ['data'=>'条件不足，旧密码或新密码为空','code'=>101];
		}
		if ($new_password != $new_again_password ) 
		{
			return ['data'=>'两次输入的新密码不一样','code'=>101];
		}
		if (strlen ($new_password)<8 || strlen ($new_again_password)<8 ) 
		{
			return ['data'=>'密码长度至少8位','code'=>101];
		}
		$userid = $this ->cache['id'];
		$pswd_exits = db('fox_admin_user') -> where(['id'=>$userid]) -> find();
		if (fox_password($old_password)!=$pswd_exits['password']) 
		{
			return ['data'=>'旧密码错误','code'=>101];
		}
		if (fox_password($new_password)==$pswd_exits['password']) 
		{
			return ['data'=>'新密码和旧密码不能一样','code'=>101];
		}
		$status = db('fox_admin_user') -> where(['id'=>$userid])->update(['password'=>fox_password($new_password)]);
		if ($status) 
		{
			$info =  $this->request->header();
			$token = isset($info['authorization'])?$info['authorization']:null;
			cache('Auth_'.$token, null);
			return ['data'=>'修改成功','code'=>200];
		}
		return ['data'=>'修改失败','code'=>101];
	}
}