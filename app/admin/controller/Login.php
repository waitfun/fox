<?php
namespace app\admin\controller;
use app\common\lib\HttpExceptions;
use think\Request;
use \Firebase\JWT\JWT;
use think\Cache;

class Login
{
	protected $request;
	public function __construct(Request $request)
    {
		$this->request = $request;
    }
	public function login()
	{
		$data = $this->request->param('data');
		$username = $data['username'];
		$password = $data['password'];
		if (empty($username)||empty($password)) 
		{
			return false;
		}
		$result = db('fox_admin_user') -> where(['name'=>$username,'password'=>$password,'status'=>0]) ->field('id,name as username,email,phone,avatar')-> find();
		if ($result) 
		{
			//取出角色id
			$role_group = db('fox_role_group') -> where(['user_id'=>$result['id']]) -> find();
			$result['role_id'] = $role_group['role_id'];
			//取出角色类型
			$auth_role = db('fox_auth_role') -> where(['id'=>$role_group['role_id']]) -> find();
			$result['role_name'] = $auth_role['name'];
			//取出最新的登录记录
			$login_log = db('fox_admin_login_log') ->where(['userid'=>$result['id']])->order('id desc') ->find();
			$result['login_ip'] = $login_log['login_ip'];
			$result['login_time'] = date('Y-m-d H:i:s',$login_log['login_time']);
			$result['login_address'] = $login_log['login_address'];
			
			$address = $this ->curl_get('http://ip.taobao.com/service/getIpInfo.php?ip='.$this->request->ip(0, true));
			$ip_address = $address['data']['country'].' '.$address['data']['area'].' '.$address['data']['region'].' '.$address['data']['city'].' '.$address['data']['isp'];
			//登录日志
			$params = [
				'userid' => $result['id'],
				'login_time' => time(),
				'login_ip' => $this->request->ip(0, true),
				'login_address' => $ip_address
			];
			db('fox_admin_login_log') -> insert($params);
			//session('admin_user',$result);
			$token = $this -> get_token($result);
			Cache('Auth_'.$token,$result, 60*60*24*7);
			return ['data'=>$token,'code'=>200,'msg'=>'登录成功'];
		}else{
			return false;
		}

	}
	public function get_token($data)
	{
		$key = config('token_key');
		$token = [
		    "iat" => time(),//签发时间
		    "exp" => time() + 60*60*24*7,
		    "data" => $data
		];
		$jwt = JWT::encode($token, $key);
		return $jwt;
	}
	public function curl_get($url)
    {
        $info = curl_init();
        curl_setopt($info,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($info,CURLOPT_HEADER,0);
        curl_setopt($info,CURLOPT_NOBODY,0);
        curl_setopt($info,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($info,CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($info,CURLOPT_URL,$url);
        $result = json_decode(curl_exec($info),true);
        curl_close($info);
        return $result;
    }
    public function login_out()
	{
		$info =  $this->request->header();
		$token = isset($info['authorization'])?$info['authorization']:null;
		cache('Auth_'.$token, null);
		return ['data'=>'退出成功','code'=>200];
	}
	
}