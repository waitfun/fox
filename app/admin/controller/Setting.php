<?php
namespace app\admin\controller;
use think\Cache;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;
use Rcache\Rcache;

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
		$params['site_name'] = isset($input['site_name']) ? $input['site_name']:'';
		$params['site_seo_title'] =  isset($input['site_seo_title'])? $input['site_seo_title']:'';
		$params['site_seo_keywords'] =  isset($input['site_seo_keywords'])? $input['site_seo_keywords']:'';
		$params['site_seo_description'] =  isset($input['site_seo_description'])? $input['site_seo_description']:'';
		$params['website_icp'] =  isset($input['website_icp'])? $input['website_icp']:'';
		$params['website_email'] =  isset($input['website_email'])? $input['website_email']:'';
		$params['website_count'] =  isset($input['website_count'])? $input['website_count']:'';
		$params['website_beian'] =  isset($input['website_beian'])? $input['website_beian']:'';

		$status = set_system_option('website_site',$params);
		if ($status) 
		{
			cache('options_website_site',null);
			$this->success('修改成功');
		}
		$this->error('修改失败');

	}
	//获取网站信息
	public function fetch_website()
	{
		$res =get_system_option('website_site');
		if ($res) 
		{
			$this->success('获取成功',$res);
		}else{
			$this->error('获取失败');
		}
	}
	//清除缓存
	public function clean_cache()
	{
		$userid = $this ->cache['id'];
		$role_id = $this ->cache['role_id'];
		if ($role_id == 1||$userid ==1) 
		{
			//菜单缓存
			Rcache::prefix_rm('admin_menu_*');
			//网站信息缓存
			cache('options_website_setup',null);
			$this->success('删除成功');
		}else{
			$key = 'admin_menu_'.$role_id;
			cache($key,null);
			$this->success('删除成功');
		}
		
	}
}