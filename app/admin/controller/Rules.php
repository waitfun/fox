<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use app\common\lib\HttpExceptions;

class Rules extends Common
{
	public function __construct()
    {
    	parent::__construct();
	}
	public function get_all_rules()
	{
		$result   = db('fox_auth_rule')->where(['parent_id'=>0,'status'=>0])->select();
	    $res      = [];
		foreach ($result as $key => $v) { 
			$dat  = db('fox_auth_rule') 
				->where(['parent_id'=>$v['id'],'status'=>0]) 
				-> select () ;
			$v['children'] = $dat;
			$res []        = $v;
		}

	    return $res;
	}
	//删除权限
	public function del_rules()
	{
		if ($this->request->isPost())
		{
			$id = $this->request->param('id');
			$findParentID = db('fox_auth_rule') -> where(['parent_id'=>$id]) -> count();
			if ($findParentID !=0) 
			{
				return ['data'=>'存在子权限不能直接删除，请先删除子权限','code'=>'101'];
			}
			$status = db('fox_auth_rule') -> where(['id'=>$id]) -> delete();
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
	public function edit_rules()
	{
		if ($this->request->isPost())
		{
			$data       = $this->request->param('data');
			$id         = $data['id'];
			$title      = $data['title'];
			$app        = $data['app'];
			$controller = $data['controller'];
			$action     = $data['action'];
			$param      = $data['param'];
			$name       = $app.'/'.$controller.'/'.$action;
			$params = [
				'name' => $name,
				'title' => $title,
				'app'=>$app,
				'controller'=>$controller,
				'action'=>$action,
				'param'=>$param
			];
			$status = db('fox_auth_rule') ->where(['id'=>$id])->update($params);
			if ($status) 
			{
				return ['data'=>'修改成功','code'=>'200'];
			}
			return ['data'=>'修改失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加规则
	public function add_rules()
	{
		if ($this->request->isPost())
		{
			$data       = $this->request->param('data');
			$parent_id  = $data['select_value'];
			$title      = $data['title'];
			$app        = $data['app'];
			$controller = $data['controller'];
			$action     = $data['action'];
			$param      = $data['param'];
			$name       = $app.'/'.$controller.'/'.$action;
			$params = [
				'name'      => $name,
				'title'     => $title,
				'parent_id' => $parent_id,
				'app'       => $app,
				'controller'=> $controller,
				'action'    => $action,
				'param'     => $param
			];
			$status = db('fox_auth_rule') ->insert($params);
			if ($status) 
			{
				return ['data'=>'添加成功','code'=>'200'];
			}
			return ['data'=>'添加失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加下属规则
	public function add_subrules()
	{
		if ($this->request->isPost())
		{
			$data       = $this->request->param('data');
			$parent_id  = $data['id'];
			$title      = $data['title'];
			$app        = $data['app'];
			$controller = $data['controller'];
			$action     = $data['action'];
			$param      = $data['param'];
			$name       = $app.'/'.$controller.'/'.$action;
			$params = [
				'name'      => $name,
				'title'     => $title,
				'parent_id' => $parent_id,
				'app'       => $app,
				'controller'=> $controller,
				'action'    => $action,
				'param'     => $param
			];
			$status = db('fox_auth_rule') ->insert($params);
			if ($status) 
			{
				return ['data'=>'添加成功','code'=>'200'];
			}
			return ['data'=>'添加失败','code'=>'101'];
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加授权角色
	public function add_role()
	{
		if ($this->request->isPost())
		{
			
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	
}