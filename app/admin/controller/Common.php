<?php
namespace app\admin\controller;
use think\Request;
use auth\Auth;
use think\Controller;
use \Firebase\JWT\JWT;
use app\common\lib\HttpExceptions;

class Common //extends Controller
{
	protected $request;
	protected $cache;
	public function __construct()
    {
		$this->request = request();
		$info =  $this->request->header();
		$token = isset($info['authorization'])?$info['authorization']:null;
		if (empty($token)) 
		{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}
		$decoded_token = JWT::decode($token, config('token_key'), array('HS256'));
		if ($decoded_token->exp <time())
		{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}
		if (null == cache('Auth_'.$token))
		{
			throw new HttpExceptions('未授权', 'Unauthorized');
		}
		$userId = $decoded_token->data->id;
		$this ->cache = cache('Auth_'.$token);
		$this -> checkAccess($userId);
    }
     /**
     *  检查后台用户访问权限
     * @param int $userId 后台用户id
     * @return boolean 检查通过返回true
     */
    public function checkAccess($userId)
    {
    	//$userId = 1;
       // 如果用户id是1，则无需判断
        if ($userId == 1) {
            return true;
        }

        $module     = $this->request->module();
        $controller = $this->request->controller();
        $action     = $this->request->action();
        $rule       = strtolower($module . "/" . $controller . "/" . $action);
        $notRequire = ["admin/user/login_out"];
        if (!in_array($rule, $notRequire)) {
            $auth       = new Auth();
	        $status     = $auth -> check($userId,$rule);
	        if ($status == false)
	        {
	        	throw new HttpExceptions('没有权限', 'Forbidden');
	        }
        } 
       
       
    }
   
}