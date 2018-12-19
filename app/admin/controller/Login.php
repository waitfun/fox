<?php
namespace app\admin\controller;
use app\common\lib\HttpExceptions;
use think\Request;
use app\common\lib\Token;
use think\Cache;
use app\admin\controller\Common;

class Login extends Common
{
	protected $request;
	public function __construct(Request $request)
    {
		$this->request = $request;
    }
    /**
	 * @api {post} admin/login/login 登录
	 * @apiName login
	 * @apiGroup login
	 * @apiParam {string} username 用户名.
	 * @apiParam {string} password 密码.
	 * @apiSuccess {String} token token.
	 * @apiSuccess {int} code  状态码.
	 */
	public function login()
	{
		$input = $this->request->param();
		$username = isset($input['username']) ? $input['username'] : $this->error('username参数不存在');
		$password = isset($input['password']) ? $input['password'] : $this->error('password参数不存在');
		$result = db('admin_user') 
					-> where(['name'=>$username]) 
					-> find();
		if(empty($result))
		{
			$this->error('用户名不存在');
		}else {
            switch ($result['status']) 
            {
                case -1:
                    $this->error('此账户已被禁用!');
                case 2:
                    $this->error('账户还没有验证成功!');
            }
            if (fox_password($password)!=$result['password']) 
			{
                $this->error("密码不正确!");
            }
        }
		//取出角色id
		$role_group             = db('role_group') -> where(['user_id'=>$result['id']]) -> find();
		$result['role_id']      = $role_group['role_id'];
		//取出角色类型
		$auth_role              = db('auth_role') -> where(['id'=>$role_group['role_id']]) -> find();
		$result['role_name']    = $auth_role['name'];
		//取出最新的登录记录
		$login_log              = db('admin_login_log') ->where(['userid'=>$result['id']])->order('id desc') ->find();
		$result['login_ip']     = $login_log['login_ip'];
		$result['login_time']    = date('Y-m-d H:i:s',$login_log['login_time']);
		$result['login_address'] = $login_log['login_address'];
			
		$address                 = curl_get('http://ip.taobao.com/service/getIpInfo.php?ip='.$this->request->ip(0, true));
		$ip_address              = $address['data']['country'].' '.$address['data']['area'].' '.$address['data']['region'].' '.$address['data']['city'].' '.$address['data']['isp'];
			//登录日志
		$params = [
			'userid'        => $result['id'],
			'login_time'    => time(),
			'login_ip'      => $this->request->ip(0, true),
			'login_address' => $ip_address
		];
		$status = db('admin_login_log') -> insert($params);
			
		if ($status) 
		{
			$token = Token::buildAccessToken($result);
			Cache('Auth_'.$token,$result, 60*60*24*7);
			$this -> success('登录成功',$token);
		}else{
			$this -> error('登录失败');
		}

	}
	
	
    public function login_out()
	{
		$info =  $this->request->header();
		$token = isset($info['authorization'])?$info['authorization']:null;
		if (cache('Auth_'.$token, null)) 
		{
			$this -> success('退出成功');
		}
		$this -> error('退出失败');
	}
	
}