<?php
namespace app\admin\controller;
use think\Request;
use auth\Auth;
use think\Cache;
use app\admin\controller\Common;

class Menu extends Common
{
	public function __construct()
    {
    	parent::__construct();
	}
	//获取后台显示菜单
    public function admin_menu()
	{
		$userid = $this ->cache['id'];
		if ($userid == 1) 
		{
			$result   = db('fox_admin_menu')->where(['parent_id'=>0,'status'=>0])->select();
		    $res      = [];
			foreach ($result as $key => $v) { 
				$dat  = db('fox_admin_menu') 
					->where(['parent_id'=>$v['id'],'status'=>0]) 
					-> select () ;
				$v['children'] = $dat;
				$res []        = $v;
			}

		    return $res;
		}else{
			$role_id       = $this ->cache['role_id'];
			
			$result   = db('fox_admin_menu_access')->where(['parent_id'=>0,'status'=>0,'role_id'=> $role_id])->select();
		    $res      = [];
			foreach ($result as $key => $v) { 
				$dat  = db('fox_admin_menu_access') 
					->where(['parent_id'=>$v['menu_id'],'status'=>0,'role_id'=> $role_id]) 
					-> select () ;
				$v['children'] = $dat;
				$res []        = $v;
			}

		    return $res;
		}
		
	}
	//获取所有菜单
	public function get_all_menu()
	{
		$result   = db('fox_admin_menu')
					->where(['parent_id'=>0,'status'=>0])
					->field('id,title,name,icon')
					->select();
	    $res      = [];
		foreach ($result as $key => $v) { 
			$dat  = db('fox_admin_menu') 
					->where(['parent_id'=>$v['id'],'status'=>0]) 
					->field('id,title,name,icon')
					-> select () ;
			$v['children'] = $dat;
			
			$res []        = $v;

		}
	    return $res;
	}
	//删除菜单
	public function del_menu()
	{
		if ($this->request->isPost())
		{
			$id = $this->request->param('id');
			$findParentID = db('fox_admin_menu') -> where(['parent_id'=>$id]) -> count();
			if ($findParentID !=0) 
			{
				return ['data'=>'存在子菜单不能直接删除，请先删除子菜单','code'=>'101'];
			}
			$status = db('fox_admin_menu') -> where(['id'=>$id]) -> delete();
			if ($status) 
			{
				return ['data'=>'删除成功','code'=>'200'];
			}
			return ['data'=>'删除失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//修改
	public function edit_menu()
	{
		if ($this->request->isPost())
		{
			$data = $this->request->param('data');
			$name = $data['name'];
			$title = $data['title'];
			$id = $data['id'];
			$params = [
				'name' => $name,
				'title' => $title,
			];
			$status = db('fox_admin_menu') ->where(['id'=>$id])->update($params);
			if ($status) 
			{
				return ['data'=>'修改成功','code'=>'200'];
			}
			return ['data'=>'修改失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加子菜单
	public function add_submenu()
	{
		if ($this->request->isPost())
		{
			$data = $this->request->param('data');
			$name = $data['name'];
			$title = $data['title'];
			$id = $data['id'];
			$params = [
				'name' => $name,
				'title' => $title,
				'parent_id'=>$id
			];
			$status = db('fox_admin_menu') ->insert($params);
			if ($status) 
			{
				return ['data'=>'添加成功','code'=>'200'];
			}
			return ['data'=>'添加失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加一级菜单
	public function add_menu()
	{
		if ($this->request->isPost())
		{
			$data = $this->request->param('data');
			$name = $data['name'];
			$title = $data['title'];
			$icon = $data['icon'];
			$params = [
				'name' => $name,
				'title' => $title,
				'icon'=>$icon
			];
			$status = db('fox_admin_menu') ->insert($params);
			if ($status) 
			{
				return ['data'=>'添加成功','code'=>'200'];
			}
			return ['data'=>'添加失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	public function get_access_menu()
	{
		if ($this->request->isPost())
		{
			$role_id  =  $this->request->param('id');
			$result   = db('fox_admin_menu_access')
						->where(['parent_id'=>0,'status'=>0,'role_id'=>$role_id])
						->select();
		    $res      = [];
			foreach ($result as $key => $v) { 
				$dat  = db('fox_admin_menu_access') 
						->where(['parent_id'=>$v['menu_id'],'status'=>0,'role_id'=>$role_id]) 
						-> select () ;
				$v['children'] = $dat;
				$res []        = $v;
			}
	    	return $res;
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//获取所有授权菜单，渲染已经授权
	public function access_menu()
	{
		if ($this->request->isPost())
		{
			$role_id       =  $this->request->param('id');
			$menu_access   =  db('fox_admin_menu_access')
							  ->field('menu_id as id,title')
							  ->where(['status'=>0,'role_id'=> $role_id])
							  ->select();
			$rule_access   =  db('fox_auth_access')
							  ->field('rule_id as id,title')
							  ->where(['role_id'=>$role_id])
							  ->select();
			$data          = ['menu_access'=>$menu_access,'rule_access'=>$rule_access];
			return $data;
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	
}