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
		$input         = $this->request->param();
		$old_password  = isset($data['old_password'])?$data['old_password']:$this->error('缺少old_password参数');
		$new_password  = isset($data['new_password'])?$data['new_password']:$this->error('缺少new_password参数');
		$new_again_password  = isset($data['new_again_password'])?$data['new_again_password']:$this->error('缺少new_again_password参数');
		
		if (strlen ($new_password)<8 || strlen ($new_again_password)<8 ) 
		{
			$this->error('密码长度至少8位');
		}
		$userid = $this ->cache['id'];
		$pswd_exits = db('admin_user') -> where(['id'=>$userid]) -> find();
		if (fox_password($old_password)!=$pswd_exits['password']) 
		{
			$this->error('旧密码错误');
		}
		if (fox_password($new_password)==$pswd_exits['password']) 
		{
			$this->error('新密码和旧密码不能一样');
		}
		$status = db('admin_user') -> where(['id'=>$userid])->update(['password'=>fox_password($new_password)]);
		if ($status) 
		{
			$info =  $this->request->header();
			$token = isset($info['authorization'])?$info['authorization']:null;
			cache('Auth_'.$token, null);
			$this->success('修改成功');
		}
		$this->error('修改失败');
	}
	//设置网站信息
	public function create_website()
	{
		$input       = $this->request->param();
		$params['site_name'] = $input['site_name'];
		$params['site_seo_title'] = $input['site_seo_title'];
		$params['site_seo_keywords'] = $input['site_seo_keywords'];
		$params['site_seo_description'] = $input['site_seo_description'];
		$params['website_icp'] = $input['website_icp'];
		$params['website_email'] = $input['website_email'];
		$params['website_count'] = $input['website_count'];
		$params['website_beian'] = $input['website_beian'];

		$status = db('website_setup') -> where(['id'=>1]) -> update($params);
		if ($status) 
		{
			$this->success('修改成功');
		}
		$this->error('修改失败');

	}
	//获取网站信息
	public function fetch_website()
	{
		$res = db('website_setup') -> find();
		if ($res) 
		{
			$this->success('获取成功',$res);
		}
		$this->error('获取失败');
	}
}