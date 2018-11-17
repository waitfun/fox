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
/**
 * 中文字符串截取
 * @param string      $str    待截取的字符串
 * @param int|integer $start  开始位置
 * @param int|null    $length 截取长度，默认截取到字符串末尾
 */
function zhSubstr($str, $start = 0, $length = NULL)
{
    $count = 0;
    $offset = $start;
    $len = strlen($str);
    for ($i=0; $i < $len; $i++) {
        if (($count - $start) == $length) {
            break;
        }
        if (preg_match("/^[".chr(0xa1)."-".chr(0xff)."]+$/", substr($str, $i, 1))) {
            $i += 2;
        }
        ++$count;
        if ($count == $start) {
            $offset = $i + 1;
        }
    }
    return substr($str, $offset, $length?($i - $offset):strlen($str));
}
