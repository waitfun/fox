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
	public function get_all_role()
	{
		$result   = db('fox_auth_role')->field('id,create_time,name,remark,status')->select();
	    return $result;
	}
	//修改
	public function edit_rabc()
	{
		if ($this->request->isPost())
		{
			$data       = $this->request->param('data');
			$id         = $data['id'];
			$name       = $data['name'];
			$remark     = $data['remark'];
			$status 	= $data['status'];
			$update_time= time();
			
			$params = [
				'name'        => $name,
				'remark'      => $remark,
				'status'      => $status,
				'update_time' => $update_time,
			];
			$status = db('fox_auth_role') ->where(['id'=>$id])->update($params);
			if ($status) 
			{
				return ['data'=>'修改成功','code'=>'200'];
			}
			return ['data'=>'修改失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加角色
	public function add_role()
	{
		if ($this->request->isPost())
		{
			$data       = $this->request->param('data');
			$status     = $data['status'];
			$name       = $data['name'];
			$remark     = $data['remark'];
			$create_time= time();
			
			$params = [
				'name'        => $name,
				'remark'      => $remark,
				'status'      => $status,
				'create_time' => $create_time,
			];
			$status = db('fox_auth_role') ->insert($params);
			if ($status) 
			{
				return ['data'=>'添加成功','code'=>'200'];
			}
			return ['data'=>'添加失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//删除
	public function del_role()
	{
		if ($this->request->isPost())
		{
			$id     = $this->request->param('id');
			db('fox_auth_access') -> where(['role_id'=>$id]) -> delete();
			$status = db('fox_auth_role') -> where(['id'=>$id]) -> delete();
			if ($status) 
			{
				return ['data'=>'删除成功','code'=>'200'];
			}
			return ['data'=>'删除失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//授权
	public function add_auth()
	{
		if ($this->request->isPost())
		{
			$data       = $this->request->param('data');
			$id         = $data['id'];
			$menu_data  = isset($data['menu_data']) ? $data['menu_data'] : null;
			$rule_data  = isset($data['rule_data']) ? $data['rule_data'] : null;
			//菜单授权操作
			if ($menu_data != null) 
			{
				db('fox_admin_menu_access') -> where(['role_id'=>$id]) -> delete();
				foreach ($menu_data as $key => $v) 
				{
					
					$child_menu_data  = db('fox_admin_menu') -> where(['id'=>$v['id']])-> find();
					
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
						db('fox_admin_menu_access') ->insert($param_2);
					}

				}
			}else{
				//当没有数据时，清除当前角色授权
				db('fox_admin_menu_access') -> where(['role_id'=>$id]) -> delete();
			}
			//规则授权
			if ($rule_data != null) 
			{
				db('fox_auth_access') -> where(['role_id'=>$id]) -> delete();
				foreach ($rule_data as $key => $v) 
				{
					
					$rule_menu_data  = db('fox_auth_rule') -> where(['id'=>$v['id']])-> find();
					
					if (!empty($rule_menu_data)) 
					{
						$param_3 = [
							'role_id'    => $id,
							'rule_name'  => $rule_menu_data['name'],
							'title'      => $rule_menu_data['title'],
							'rule_id'    => $rule_menu_data['id']
						];
						db('fox_auth_access') ->insert($param_3);
					}
					
				}
				
			}else{
				//当没有数据时，清除当前角色授权
				db('fox_auth_access') -> where(['role_id'=>$id]) -> delete();
			}
			return ['data'=>'授权成功','code'=>'200'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
}