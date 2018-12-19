<?php
namespace app\common\lib;
use \Firebase\JWT\JWT;

class Token 
{
	 /**
     * 生成AccessToken
     * @return string
     */
    public static function buildAccessToken($data)
    {
		$key   = config('token_key');
		$token = [
		    "iat"  => time(),//签发时间
		    "exp"  => time() + config('token_exp'),
		    "data" => $data
		];
		$access_token = JWT::encode($token, $key);
		return $access_token;
    }
     /**
     * 解密AccessToken
     * @return string
     */
    public static function decryptAccessToken($token)
    {
		$key 		   = config('token_key');
		$decoded_token = JWT::decode($token, $key, array('HS256'));
		$access_token  = json_decode(json_encode($decoded_token,JSON_UNESCAPED_UNICODE),true);
		return $access_token;
    }
    

}