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
	/**
	 * @api {post} admin/rules/get_all_rules 获取所有授权规则(权限设置)
	 * @apiName get_all_rules
	 * @apiGroup rules
	 * @apiSuccess {String} title 权限名称.
	 * @apiSuccess {String} name 权限url.
	 * @apiSuccess {int} parent_id  父id.
	 * @apiSuccess {String} children  子分类.
	 * @apiSuccessExample {json} 结果:
	 *[{
			"id": 163,
			"parent_id": 0,
			"name": "admin\/menu\/default",
			"title": "菜单管理",
			"children": [{
				"id": 166,
				"parent_id": 163,
				"name": "admin\/menu\/get_all_menu",
				"title": "获取所有菜单",
			}]
	 *	}]
	 */
	public function get_all_rules()
	{
		$result   = db('auth_rule')
			->where(['status'=>0])
			->field('id,parent_id,title,name')
			->select();
	    $res      = $this -> tree($result);
	    if ($res) 
	    {
	   		$this->success('获取成功',$res);
	    }
	    $this->error('获取失败');
	}
	/**
	 * @api {post} admin/rules/del_rules 删除权限规则(权限设置)
	 * @apiName del_rules
	 * @apiGroup rules
	 * @apiParam {int} id 规则id.
	 * @apiSuccess {String} data 修改成功.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function del_rules()
	{
		if ($this->request->isPost())
		{
			$input        = $this->request->param();
			$id           = isset($input['id']) ? $input['id'] : $this->error('缺少id参数');
			$findParentID = db('auth_rule') -> where(['parent_id'=>$id]) -> count();
			if ($findParentID !=0) 
			{
				$this->error('存在子权限不能直接删除，请先删除子权限');
			}
			$status = db('auth_rule') -> where(['id'=>$id]) -> delete();
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
	 * @api {post} admin/rules/edit_rules 修改权限规则(权限设置)
	 * @apiName edit_rules
	 * @apiGroup rules
	 * @apiParam {int} id 规则id.
	 * @apiParam {String} title 规则名称.
	 * @apiParam {String} app app.
	 * @apiParam {String} controller controller.
	 * @apiParam {String} action action.
	 * @apiParam {String} param param.
	 * @apiSuccess {String} data 数据集.
	 * @apiSuccess {int} msg  信息提示.
	 * @apiSuccess {int} code  状态码.
	 */
	public function edit_rules()
	{
		if ($this->request->isPost())
		{
			$input                = $this->request->param();
			$id                   = isset($input['id']) ? $input['id'] : $this->error('缺少id参数');
			$params['title']      = isset($input['title']) ? $input['title'] : $this->error('缺少title参数');
			$params['app']        = isset($input['app']) ? $input['app'] : $this->error('缺少app参数');
			$params['controller'] = isset($input['controller']) ? $input['controller'] : $this->error('缺少controller参数');
			$params['action']     = isset($input['action']) ? $input['action'] : $this->error('缺少action参数');
			$params['param']     = isset($input['param']) ? $input['param'] : $this->error('缺少param参数');
			$params['name']      = $params['app'].'/'.$params['controller'].'/'.$params['action'];
			
			$status = db('auth_rule') ->where(['id'=>$id])->update($params);
			if ($status) 
			{
				$this->success('修改成功');
			}
			$this->error('修改成功');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加规则
	public function add_rules()
	{
		if ($this->request->isPost())
		{
			$input                = $this->request->param();
			$params['parent_id']  = isset($input['parent_id']) ? $input['parent_id'] : 0;
			$params['title']      = isset($input['title']) ? $input['title'] : $this->error('缺少title参数');
			$params['app']        = isset($input['app']) ? $input['app'] : $this->error('缺少app参数');
			$params['controller'] = isset($input['controller']) ? $input['controller'] : $this->error('缺少controller参数');
			$params['action']     = isset($input['action']) ? $input['action'] : $this->error('缺少action参数');
			$params['param']     = isset($input['param']) ? $input['param'] : $this->error('缺少param参数');
			$params['name']      = $params['app'].'/'.$params['controller'].'/'.$params['action'];
			$status = db('auth_rule') ->insert($params);
			if ($status) 
			{
				$this->success('添加成功');
			}
			$this->error('添加失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加下属规则
	public function add_subrules()
	{
		if ($this->request->isPost())
		{
			$input               = $this->request->param();
			$params['parent_id'] = isset($input['parent_id']) ? $input['parent_id'] : $this->error('缺少parent_id参数');
			$params['title']     = isset($input['title']) ? $input['title'] : $this->error('缺少title参数');
			$params['app']       = isset($input['app']) ? $input['app'] : $this->error('缺少app参数');
			$params['controller']= isset($input['controller']) ? $input['controller'] : $this->error('缺少controller参数');
			$params['action']    = isset($input['action']) ? $input['action'] : $this->error('缺少action参数');
			$params['param']     = isset($input['param']) ? $input['param'] : $this->error('缺少param参数');
			$params['name']      = $params['app'].'/'.$params['controller'].'/'.$params['action'];
			
			$status = db('auth_rule') ->insert($params);
			if ($status) 
			{
				$this->success('添加成功');
			}
			$this->error('添加失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
}