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
			$this -> success('获取成功',$this->cache);
		}else{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}

	}
	/**
	 * @api {get} admin/user/get_all_man_user 获取全部管理员
	 * @apiGroup user
	 * @apiName get_all_man_user
	 * @apiParam {int} id 规则id.
	 * @apiSuccess {String} data 数据集.
	 * @apiSuccess {int} code  状态码.
	 */
	public function get_all_man_user()
	{
		$data = db('admin_user a') 
			-> field('a.id,a.name,a.nickname,a.email,a.phone,a.avatar,a.status')
			-> join('role_group b','a.id = b.user_id')
			-> field('b.role_id')
			-> join('auth_role c','c.id = b.role_id')
			-> field('c.name as role_name')
			-> order('id as')
			-> select();
		if ($data) 
		{
			$this -> success('获取成功',$data);
		}
		$this -> error('获取失败');

	}
	
	/**
	 * @api {post} admin/user/add_man_user 管理员添加
	 * @apiGroup user
	 * @apiName add_man_user
	 * @apiParam {String} email 邮箱.
	 * @apiParam {String} name 用户名.
	 * @apiParam {String} password 密码.
	 * @apiParam {int} status 状态.
	 * @apiParam {int} role_id 角色id.
	 * @apiSuccess {String} msg 信息提示.
	 * @apiSuccess {int} code  状态码.
	 */
	public function add_man_user()
	{
		$input       = $this->request->param();
		$email       = isset($input['email']) ? $input['email'] : $this->error('email参数不存在');
		$name        = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
		$password    = isset($input['password']) ? $input['password'] : $this->error('password参数不存在');
		$status 	 = isset($input['status']) ? $input['status'] : $this->error('status参数不存在');
		$role_id 	 = isset($input['role_id']) ? $input['role_id'] : $this->error('role_id参数不存在');
		if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) 
		{
			$this -> error('无效的 email 格式');
		}
		$email_exits = db('admin_user') -> where(['email'=>$email]) -> find();
		if ($email_exits) 
		{
			  $this -> error('此邮箱已存在');
		}
		$email_exits = db('admin_user') -> where(['name'=>$name]) -> find();
		if ($email_exits) 
		{
			 $this -> error('此用户名已存在');
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
		$state = db('admin_user') -> insert($params);
		if ($state) 
		{
			$data_exits = db('admin_user') -> where(['email'=>$email]) -> find();
			db('role_group') -> insert(['role_id'=>$role_id,'user_id' =>$data_exits['id']]);
			$this -> success('添加成功');
		}
		 $this -> error('添加失败');
	}
	
	/**
	 * @api {post} admin/user/del_man_user 删除管理员
	 * @apiGroup user
	 * @apiName del_man_user
	 * @apiParam {int} id 管理员id.
	 * @apiSuccess {String} msg 信息提示.
	 * @apiSuccess {int} code  状态码.
	 */
	public function del_man_user()
	{
		$input        = $this->request->param();
		$id 	     = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
		if (1==$id) 
		{
			$this -> error('此用户不能删除');
		}
		$id_exits = db('admin_user') -> where(['id'=>$id]) -> find();
		if (!$id_exits) 
		{
			$this -> error('要删除的数据不存在');
		}
		$status = db('admin_user') -> where(['id'=>$id]) -> delete();
		$state  = db('role_group') -> where(['user_id'=>$id]) -> delete();
		if ($status&&$state) 
		{
			$this -> success('删除成功');
		}
		$this -> error('删除失败');

	}
	/**
	 * @api {post} admin/user/edit_man_user 编辑管理员
	 * @apiGroup user
	 * @apiName edit_man_user
	 * @apiParam {String} email 邮箱.
	 * @apiParam {String} name 用户名.
	 * @apiParam {String} password 密码.
	 * @apiParam {int} status 状态.
	 * @apiParam {int} role_id 角色id.
	 * @apiSuccess {String} msg 信息提示.
	 * @apiSuccess {int} code  状态码.
	 */
	public function edit_man_user()
	{
		$input        = $this->request->param();
	    $id 	     = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
		$email       = isset($input['email']) ? $input['email'] : $this->error('email参数不存在');
		$name        = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
		$password    = isset($data['password'])?$data['password']:null;
		$status 	 = isset($input['status']) ? $input['status'] : $this->error('status参数不存在');
		$role_id 	 = isset($input['role_id']) ? $input['role_id'] : $this->error('role_id参数不存在');
		if (1==$id) 
		{
			$this -> error('此用户不能修改');
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
			$status = db('admin_user') -> where(['id'=>$id]) -> update($params);
			$status1 = db('role_group') -> where(['user_id'=>$id]) -> update(['role_id'=>$role_id]);
			if ($status||$status1) 
			{
				$this -> success('修改成功');
			}
			$this -> error('修改失败');

		}else{
			$params = [
				'name'        => $name,
				'email'       => $email,
				'status'      => $status,
				'update_time' => $update_time,
				'password'    => fox_password($password)
			];
			$status = db('admin_user') -> where(['id'=>$id]) -> update($params);
			$status1 = db('role_group') -> where(['user_id'=>$id]) -> update(['role_id'=>$role]);
			if ($status||$status1) 
			{
				$this -> success('修改成功');
			}
			$this -> error('修改失败');
		}
	}


}