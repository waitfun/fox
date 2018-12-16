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
	//获取全部管理员
	public function get_all_man_user()
	{
		$data = db('fox_admin_user a') 
			-> field('a.id,a.name,a.nickname,a.email,a.phone,a.avatar,a.status')
			-> join('fox_role_group b','a.id = b.user_id')
			-> field('b.role_id')
			-> join('fox_auth_role c','c.id = b.role_id')
			-> field('c.name as role_name')
			-> order('id as')
			-> select();
		
		return $data;

	}
	
	//管理员添加
	public function add_man_user()
	{
		$data        = $this->request->param('data');
		if (!is_array($data)) 
		{
			return ['data'=>'数据格式为数组','code'=>101];
		}
		$email       = $data['email'];
		$name        = $data['name'];
		$password    = $data['password'];
		$status 	 = $data['status'];
		$role 	 = $data['role'];
		if (empty($email)||empty($name)||empty($password)||empty($password)) 
		{
			return ['data'=>'条件不足，邮箱或密码或用户名或角色为空','code'=>101];
		}
		if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) 
		{
		  return ['data'=>'无效的 email 格式','code'=>101];
		}
		$email_exits = db('fox_admin_user') -> where(['email'=>$email]) -> find();
		if ($email_exits) 
		{
			 return ['data'=>'此邮箱已存在','code'=>101];
		}
		$email_exits = db('fox_admin_user') -> where(['name'=>$name]) -> find();
		if ($email_exits) 
		{
			 return ['data'=>'此用户名已存在','code'=>101];
		}
		$create_time = time();
		$params = [
			'name'        => $name,
			'password'    => fox_password($password),
			'email'       => $email,
			'status'      => $status,
			'create_time' => $create_time,
			'avatar'      => 'avatar.jpg'
		];
		$state = db('fox_admin_user') -> insert($params);
		if ($state) 
		{
			$data_exits = db('fox_admin_user') -> where(['email'=>$email]) -> find();
			db('fox_role_group') -> insert(['role_id'=>$role,'user_id' =>$data_exits['id']]);
			return ['data'=>'添加成功','code'=>200];
		}
		return ['data'=>'添加失败','code'=>101];
	}
	
	//删除管理员
	public function del_man_user()
	{
		$id        = $this->request->param('id');
		if (empty($id)) 
		{
			return ['data'=>'参数为空','code'=>101];
		}
		if (1==$id) 
		{
			return ['data'=>'此用户不能删除','code'=>101];
		}
		$id_exits = db('fox_admin_user') -> where(['id'=>$id]) -> find();
		if (!$id_exits) 
		{
			return ['data'=>'要删除的数据不存在','code'=>101];
		}
		$status = db('fox_admin_user') -> where(['id'=>$id]) -> delete();
		if ($status) 
		{
			db('fox_role_group') -> where(['user_id'=>$id]) -> delete();
			return ['data'=>'删除成功','code'=>200];
		}
		return ['data'=>'删除失败','code'=>101];
	}
	//编辑管理员
	public function edit_man_user()
	{
		$data        = $this->request->param('data');
		if (!is_array($data)) 
		{
			return ['data'=>'数据格式为数组','code'=>101];
		}
		$email       = $data['email'];
		$name        = $data['name'];
		$password    = isset($data['password'])?$data['password']:null;
		$status 	 = $data['status'];
		$role 	 	 = $data['role_id'];
		$id 	     = $data['id'];
		if (empty($email)||empty($name)||empty($role)) 
		{
			return ['data'=>'条件不足，邮箱或密码或用户名或角色为空','code'=>101];
		}
		if (1==$id) 
		{
			return ['data'=>'此用户不能修改','code'=>101];
		}
		$update_time = time();
		
		if (empty($password)) 
		{
			$params = [
				'name'        => $name,
				'email'       => $email,
				'status'      => $status,
				'update_time' => $update_time
			];
			$status = db('fox_admin_user') -> where(['id'=>$id]) -> update($params);
			$status1 = db('fox_role_group') -> where(['user_id'=>$id]) -> update(['role_id'=>$role]);
			if ($status||$status1) {
				return ['data'=>'修改成功','code'=>200];
			}
			return ['data'=>'失败','code'=>101];
		}else{
			$params = [
				'name'        => $name,
				'email'       => $email,
				'status'      => $status,
				'update_time' => $update_time,
				'password'    => fox_password($password)
			];
			$status = db('fox_admin_user') -> where(['id'=>$id]) -> update($params);
			$status1 = db('fox_role_group') -> where(['user_id'=>$id]) -> update(['role_id'=>$role]);
			if ($status||$status1) {
				return ['data'=>'修改成功','code'=>200];
			}
			return ['data'=>'修改失败','code'=>101];
		}
	}
	
}