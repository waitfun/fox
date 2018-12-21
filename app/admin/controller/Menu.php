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
	//获取后台显示菜单，此处不用做权限验证
	/**
	 * @api {get} admin/menu/admin_menu 获取后台显示菜单(左侧显示)
	 * @apiName admin_menu
	 * @apiGroup menu
	 * @apiSuccess {String} title 菜单名称.
	 * @apiSuccess {String} name 菜单url.
	 * @apiSuccess {int} parent_id  父id.
	 * @apiSuccess {String} children  子分类.
	 * @apiSuccess {String} icon  菜单图标.
	 * @apiSuccessExample {json} 返回结果:
	 *{
     *  "id": 1,
     *  "parent_id": 0,
     *  "title": "系统设置",
     *  "name": "system_set",
     *  "icon": "el-icon-setting",
     *  "children": [
     *      {
     *         "id": 3,
     *         "parent_id": 1,
     *         "title": "系统设置",
     *         "name": "网站信息",
     *         "icon": "",
     *      }
     *  ]
     *}
	 */
    public function admin_menu()
	{
		$userid = $this ->cache['id'];
		$role_id = $this ->cache['role_id'];
		if ($userid == 1||$role_id ==1) 
		{
			$result   = db('admin_menu')
				->where(['status'=>0])
				->field('id,parent_id,title,name,icon')
				->select();
		    $res      = $this -> tree($result);

		   $this -> success('获取成功',$res);
		}else{
			
			$result   = db('admin_menu_access')->where(['status'=>0,'role_id'=> $role_id])->select();
		    $res      = $this -> tree($result);
			if(empty($res))
			{
				return db('admin_menu')->where(['id'=>1,'status'=>0])->select();
			}
		    return $res;
		}
		
	}
	//获取所有菜单
	/**
	 * @api {get} admin/menu/get_all_menu 获取所有菜单(权限设置)
	 * @apiName get_all_menu
	 * @apiGroup menu
	 * @apiSuccess {String} title 菜单名称.
	 * @apiSuccess {String} name 菜单url.
	 * @apiSuccess {int} parent_id  父id.
	 * @apiSuccess {String} children  子分类.
	 * @apiSuccess {String} icon  菜单图标.
	 */
	public function get_all_menu()
	{
		$result   = db('admin_menu')
					->where(['status'=>0])
					->field('id,parent_id,title,name,icon')
					->select();
		$res      = $this -> tree($result);
		if ($res) 
		{
			$this->success('获取成功',$res);
		}
		$this->error('获取失败');
	}
	//删除菜单
	/**
	 * @api {post} admin/menu/del_menu 删除菜单(权限设置)
	 * @apiName del_menu
	 * @apiGroup menu
	 * @apiParam {Object[]} id 某一菜单的id.
	 * @apiSuccess {String} data 删除成功.
	 * @apiSuccess {String} code 200.
	 * @apiError {String} data 删除失败.
	 * @apiError {int} code 101.
	 */
	public function del_menu()
	{
		if ($this->request->isPost())
		{
			$input        = $this->request->param();
			$id           = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
			$findParentID = db('admin_menu') -> where(['parent_id'=>$id]) -> count();
			if ($findParentID !=0) 
			{
				$this->error('存在子菜单不能直接删除，请先删除子菜单');
			}
			$status = db('admin_menu') -> where(['id'=>$id]) -> delete();
			if ($status) 
			{
				$this->success('删除成功');
			}
			$this->error('删除失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//修改
	 /**
	 * @api {post} admin/menu/edit_menu 修改菜单(权限设置)
	 * @apiName edit_menu
	 * @apiGroup menu
	 * @apiParam {Object[]} data 请求数据类型为数组，里面携带name,title等参数.
	 * @apiParam {string} name 菜单url.
	 * @apiParam {string} title 菜单名称.
	 * @apiParam {int} id 菜单id.
	 * @apiSuccess {String} data 修改成功.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function edit_menu()
	{
		if ($this->request->isPost())
		{
			$input           = $this->request->param();
			$params['name']  = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
			$params['title'] = isset($input['title']) ? $input['title'] : $this->error('title参数不存在');
			$id              = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
			$status          = db('admin_menu') ->where(['id'=>$id])->update($params);
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
	 * @api {post} admin/menu/add_submenu 添加子菜单(权限设置)
	 * @apiName add_submenu
	 * @apiGroup menu
	 * @apiParam {Object[]} data 请求数据类型为数组，里面携带name,title等参数.
	 * @apiParam {string} name 菜单url.
	 * @apiParam {string} title 菜单名称.
	 * @apiParam {int} id 菜单父id.
	 * @apiSuccess {String} data 添加成功.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function add_submenu()
	{
		if ($this->request->isPost())
		{
			$input = $this->request->param();
			$params['name']  = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
			$params['title'] = isset($input['title']) ? $input['title'] : $this->error('title参数不存在');
			$params['parent_id'] = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
			$status = db('admin_menu') ->insert($params);
			if ($status) 
			{
				$this->success('添加成功');
			}
			$this->error('添加失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//添加一级菜单
	 /**
	 * @api {post} admin/menu/add_submenu 添加一级菜单(权限设置)
	 * @apiName add_submenu
	 * @apiGroup menu
	 * @apiParam {Object[]} data 请求数据类型为数组，里面携带name,title等参数.
	 * @apiParam {string} name 菜单url.
	 * @apiParam {string} title 菜单名称.
	 * @apiParam {string} icon 菜单icon 例如el-icon-setting.
	 * @apiSuccess {String} data 添加成功.
	 * @apiSuccess {int} code  状态码，200成功.
	 */
	public function add_menu()
	{
		if ($this->request->isPost())
		{
			$input = $this->request->param();
			$params['parent_id']  = isset($input['parent_id']) ? $input['parent_id'] : 0;
			$params['name']  = isset($input['name']) ? $input['name'] : $this->error('name参数不存在');
			$params['title'] = isset($input['title']) ? $input['title'] : $this->error('title参数不存在');
			$params['icon']  = isset($input['icon']) ? $input['icon'] : $this->error('icon参数不存在');
			$status = db('admin_menu') ->insert($params);
			if ($status) 
			{
				$this->success('添加成功');
			}
			$this->error('添加失败');
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	//获取所有授权菜单，渲染已经授权
	 /**
	 * @api {post} admin/menu/access_menu 显示授权的菜单(权限设置)
	 * @apiName access_menu
	 * @apiGroup menu
	 * @apiParam {string} role_id 角色id,例如普通管理员id为2.
	 */
	public function access_menu()
	{
		if ($this->request->isPost())
		{
			$input         =  $this->request->param();
			$role_id       = isset($input['id']) ? $input['id'] : $this->error('id参数不存在');
			$menu_access   =  db('admin_menu_access')
							  ->field('menu_id as id,title')
							  ->where(['status'=>0,'role_id'=> $role_id])
							  ->select();
			$rule_access   =  db('auth_access')
							  ->field('rule_id as id,title')
							  ->where(['role_id'=>$role_id])
							  ->select();
			$data          = ['menu_access'=>$menu_access,'rule_access'=>$rule_access];
			$this->success('获取成功',$data);
		}else{
			throw new HttpExceptions('请求方法错误', 'MethodNotAllowed');
		}
	}
	
}