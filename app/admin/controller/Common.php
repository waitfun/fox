<?php
namespace app\admin\controller;
use think\Request;
use auth\Auth;
use think\exception\HttpResponseException;
use app\common\lib\HttpExceptions;
use think\Response;
use app\common\lib\Token;

class Common 
{
	protected $request;
	protected $cache;
	protected $response;
	public function __construct()
    {
		$this->request  = request();
		$info          =  $this->request->header();
		$token         = isset($info['authorization'])?$info['authorization']:null;
		if (empty($token)) 
		{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}
		$access_token = Token::decryptAccessToken($token);
		if ($access_token['exp'] < time())
		{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}
		if (null == cache('Auth_'.$token))
		{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}
		
		$this ->cache = cache('Auth_'.$token);
		$role_id      = $this ->cache['role_id'];
		$userid       = $this ->cache['id'];
		$this -> checkAccess($userid,$role_id);
    }
     /**
     *  检查后台用户访问权限
     * @access protected
     * @param int $userId 后台用户id
     * @return boolean 检查通过返回true
     */
    protected function checkAccess($userId,$role_id)
    {
       // 如果用户id是1，则无需判断
        if ($userId == 1) {
            return true;
        }
        //角色id是1，超级管理员
        if ($role_id == 1) {
            return true;
        }

        $module     = $this->request->module();
        $controller = $this->request->controller();
        $action     = $this->request->action();
        $rule       = strtolower($module . "/" . $controller . "/" . $action);
        $auth       = new Auth();
	    $status     = $auth -> check($userId,$rule);
	    if ($status == false)
	    {
	       throw new HttpExceptions('没有权限', 'Forbidden');
	    }
	    
    }
    /**
	  * 生成树形结构
	  * @access protected
	  * @param mixed $arr 数据集
	  * @return String
	  */
    protected function tree($arr)
	{
	    $items = [];
	    foreach($arr as $value){
	        $items[$value['id']] = $value;
	    }
	    $tree = [];
	    foreach($items as $key => $item){
	        if(isset($items[$item['parent_id']])){
	            $items[$item['parent_id']]['children'][] = &$items[$key];
	        }else{
	            $tree[] = &$items[$key];
	        }
	    }
	    return $tree;
	}
	/**
	  * 错误处理
	  * @access protected
	  * @param mixed $msg 提示信息
	  * @param mixed $data 返回的数据
	  * @param int $code 状态码
	  * @return void
	  */
	protected  function error($msg = '',$data = '',$code = 101)
    {
    	$result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        $type      = 'json';
        $response = Response::create($result, $type);
        throw new HttpResponseException($response);
    }
     /**
	  * 操作成功处理
	  * @access protected
	  * @param mixed $msg 提示信息
	  * @param mixed $data 返回的数据
	  * @param int $code 状态码
	  * @return void
	  */
 	protected function success($msg = '', $data = '', $code = '200')
 	{
 		$result = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];
        $type      = 'json';
        $response = Response::create($result, $type);
        throw new HttpResponseException($response);
 	}
   
}