<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;

class Rabc extends Common
{
	public function __construct()
    {
    	parent::__construct();
	}
	/**
	 * @api {post} admin/rabc/get_all_role 获取所有授权角色(权限设置)
	 * @apiGroup rabc
	 * @apiName get_all_role
	 * @apiSuccessExample {json} 返回结果:
	 *[{
			"id": 1,
			"create_time": 1329633709,
			"name": "超级管理员",
			"remark": "拥有网站最高管理员权限！",
			"status": 0
     *}, {
			"id": 2,
			"create_time": 1329633709,
			"name": "普通管理员",
			"remark": "权限由最高管理员分配！",
			"status": -1
	 *}]
	 */
	public function get_all_role()
	{
		$result   = db('auth_role')->field('id,create_time,name,remark,status')->select();
	    if ($result) 
	    {
	    	$this->success('获取成功',$result);
	    }
	    $this->error('获取失败');
	}
	 /**
	 * @api {post} admin/rabc/edit_rabc 修改角色(权限设置)
	 * @apiName edit_rabc
	 * @apiGroup rabc
	 * @apiParam {Object[]} data 请求数据类型为数组，里面携带name,remark等参数.
	 * @apiParam {string} name 角色名称.
	 * @apiParam {string} remark 角色描述.
	 * @apiParam {int} id 角色id.
	 * @apiParam {int} status 状态，0正常，-1禁用.
	 * @apiSuccess {String} data 修改成功.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function edit_rabc()
	{
		if ($this->request->isPost())
		{
			$input                 = $this->request->param();
			$id                    = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
			$params['name']        = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
			$params['remark']      = isset($input['remark']) ? $input['remark'] : $this->error('remark参数不存在');
			$params['status']  	   = isset($input['status']) ? $input['status'] : $this->error('status参数不存在');
			$params['update_time'] = time();
			
			$status = db('auth_role') ->where(['id'=>$id])->update($params);
			if ($status) 
			{
				$this->success('修改成功');
			}
			$this->error('修改失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	/**
	 * @api {post} admin/rabc/add_role 添加角色(权限设置)
	 * @apiName add_role
	 * @apiGroup rabc
	 * @apiParam {string} name 角色名称.
	 * @apiParam {string} remark 角色描述.
	 * @apiParam {int} status 状态，0正常，-1禁用.
	 * @apiSuccess {String} msg 提示信息.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function add_role()
	{
		if ($this->request->isPost())
		{
			$input                 = $this->request->param();
			$params['name']        = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
			$params['remark']      = isset($input['remark']) ? $input['remark'] : $this->error('remark参数不存在');
			$params['status']  	   = isset($input['status']) ? $input['status'] : $this->error('status参数不存在');
			$params['create_time'] = time();
			
			$status = db('auth_role') ->insert($params);
			if ($status) 
			{
				$this->success('添加成功');
			}
			$this->error('添加失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	/**
	 * @api {post} admin/rabc/del_role 删除角色(权限设置)
	 * @apiName del_role
	 * @apiGroup rabc
	 * @apiParam {int} id 角色id.
	 * @apiSuccess {String} msg 提示信息.
	 * @apiSuccess {int} code  状态码.
	 */
	public function del_role()
	{
		if ($this->request->isPost())
		{
			$input  = $this->request->param();
			$id     = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
			$state  = db('auth_access') -> where(['role_id'=>$id]) -> delete();
			$status = db('auth_role') -> where(['id'=>$id]) -> delete();
			if ($status) 
			{
				$this->success('删除成功');
			}
			$this->error('删除失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	/**
	 * @api {post} admin/rabc/add_auth 角色授权(权限设置)
	 * @apiName add_auth
	 * @apiGroup rabc
	 * @apiParam {int} id 角色id.
	 * @apiParam {string} menu_data 菜单数据.
	  * @apiParam {string} rule_data 权限规则数据.
	 * @apiSuccess {String} data 授权成功.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function add_auth()
	{
		if ($this->request->isPost())
		{
			$input      = $this->request->param();
			$id         = isset($input['id']) ? $input['id'] : $this->error('缺少id参数');
			$menu_data  = isset($input['menu_data']) ? $input['menu_data'] : null;
			$rule_data  = isset($input['rule_data']) ? $input['rule_data'] : null;
			//菜单授权操作
			if ($menu_data != null) 
			{
				db('admin_menu_access') -> where(['role_id'=>$id]) -> delete();
				foreach ($menu_data as $key => $v) 
				{
					
					$child_menu_data  = db('admin_menu') -> where(['id'=>$v['id']])-> find();
					
					if (!empty($child_menu_data)) 
					{
						$param_2 = [
							'role_id'   => $id,
							'icon'      => $child_menu_data['icon'],
							'title'     => $child_menu_data['title'],
							'name'      => $child_menu_data['name'],
							'parent_id' => $child_menu_data['parent_id'],
							'menu_id'   => $child_menu_data['id']
						];
						db('admin_menu_access') ->insert($param_2);
					}

				}
			}else{
				//当没有数据时，清除当前角色授权
				db('admin_menu_access') -> where(['role_id'=>$id]) -> delete();
			}
			//规则授权
			if ($rule_data != null) 
			{
				db('auth_access') -> where(['role_id'=>$id]) -> delete();
				foreach ($rule_data as $key => $v) 
				{
					
					$rule_menu_data  = db('auth_rule') -> where(['id'=>$v['id']])-> find();
					
					if (!empty($rule_menu_data)) 
					{
						$param_3 = [
							'role_id'    => $id,
							'rule_name'  => $rule_menu_data['name'],
							'title'      => $rule_menu_data['title'],
							'rule_id'    => $rule_menu_data['id']
						];
						db('auth_access') ->insert($param_3);
					}
					
				}
				
			}else{
				//当没有数据时，清除当前角色授权
				db('auth_access') -> where(['role_id'=>$id]) -> delete();
			}
			$this->success('授权成功');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	/**
	 * @api {post} admin/rabc/get_auth_role 获取授权角色
	 * @apiName get_auth_role
	 * @apiGroup rabc
	 * @apiSuccess {String} data 数据集.
	 * @apiSuccess {String} msg 提示信息.
	 * @apiSuccess {int} code  状态码.
	 */
	public function get_auth_role()
	{
		$res = db('auth_role') -> where(['status'=>0]) -> select();
		if ($res) 
		{
			$this -> success('获取成功',$res);
		}
		$this -> error('获取失败');
	}
}