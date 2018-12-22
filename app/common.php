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
