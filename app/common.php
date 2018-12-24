<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件
use think\Config;
/**
 * 密码加密方法
 * @param string $pw 要加密的原始密码
 * @param string $authCode 加密字符串
 * @return string
 */
function fox_password($pw, $authCode = '')
{
    if (empty($authCode)) {
        $authCode = Config('authcode');
    }
    $result = md5(md5($authCode . $pw));
    return $result;
} 

function curl_get($url)
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


/**
 * 设置系统配置，通用
 * @param string $key 配置键值,都小写
 * @param array $data 配置值，数组
 * @param bool $replace 是否完全替换
 * @return bool 是否成功
 */
function set_system_option($key, $data, $replace = false)
{

    $key        = strtolower($key);
    $option     = [];
    $findOption = db('website_option')->where('option_name', $key)->find();
    if ($findOption) 
    {
        if (!$replace) 
        {
            $oldOptionValue = json_decode($findOption['option_value'], true);
            if (!empty($oldOptionValue)) 
            {
                $data = array_merge($oldOptionValue, $data);
            } 
        }

        $option['option_value'] = json_encode($data,JSON_UNESCAPED_UNICODE);
        $status = db('website_option')->where('option_name', $key)->update($option);
        if ($status) 
        {
           return true;
        }
       return false;
    } else {
        $option['option_name']  = $key;
        $option['option_value'] = json_encode($data, JSON_UNESCAPED_UNICODE);
        $status = db('website_option')->insert($option);
        if ($status) 
        {
           return true;
        }
        return false;
    }
}
/**
 * 获取系统配置，通用
 * @param string $key 配置键值,都小写
 * @return array
 */
function get_system_option($key)
{
    $key         = strtolower($key);
    $optionValue = cache('options_' . $key);

    if (empty($optionValue)) {
        $optionValue = db('website_option') ->where(['option_name'=> $key])-> find();
        if (!empty($optionValue)) {
            $optionValue = json_decode($optionValue['option_value'], true);
            cache('options_' . $key, $optionValue);
        }
    }
    return $optionValue;
}


 
//加密
function encrypt_data($data, $key='xVZrZ9RnIKNof2nbwF')
{
    $key    =   md5($key);
    $x      =   0;
    $len    =   strlen($data);
    $l      =   strlen($key);
    $char = '';
    $str = '';
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l) 
        {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

//解密
function decrypt_data($data, $key='xVZrZ9RnIKNof2nbwF')
{
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
      $char = '';
    $str = '';
    for ($i = 0; $i < $len; $i++)
    {
        if ($x == $l) 
        {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++)
    {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1)))
        {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        }
        else
        {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}
//验证码生成
function create_code($lenght)
{
    $chars = "0123456789";
    $str = "";
    for ($i = 0; $i < $lenght; $i++) {
        $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
    }
    return $str;
}
/**
 * 检查手机格式，中国手机不带国家代码，国际手机号格式为：国家代码-手机号
 * @param $mobile
 * @return bool
 */
function check_mobile($mobile)
{
    if (preg_match('/(^(13\d|14\d|15\d|16\d|17\d|18\d|19\d)\d{8})$/', $mobile)) {
        return true;
    } else {
        if (preg_match('/^\d{1,4}-\d{5,11}$/', $mobile)) {
            if (preg_match('/^\d{1,4}-0+/', $mobile)) {
                //不能以0开头
                return false;
            }
            return true;
        }
        return false;
    }
}
//验证手机验证码
function check_sms($code,$phoneNumber )
{
      
    $sms_code = cache('sms_code_'.$phoneNumber);
        //return date('Y-m-d H:i:s',strtotime(date('Y-m-d',time()))+60*60*24);
    if ($sms_code！=$code) 
    {
        $this->success('验证码不正确');
    }
    if ($sms_code == null) 
    {
        $this->error('验证码已过期');
    }
}