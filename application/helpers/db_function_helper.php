<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 获取完整的图片路径，又拍云CDN
 * @param string $img_uri 绝对、相对路径
 * @param string $size [optional] 可选缩率图大小，默认原图
 * @return string 完整的缩率图路径
 */
function out_format ($data = [], $msg = '请求成功', $mark = 'success')
{
    $data_new = [];

    $data_new['original'] = $data;

    date_default_timezone_set('PRC');
    $date = date("y-m-d H:i:s");

    $data_new['original']['update_time'] = $date;
    $data_new['original']['msg'] = $msg;
    $data_new['original']['status'] = $mark;

    return $data_new;
}

// 必填验证参数的存在
function via_param ($param = [])
{
    $mark = true;

    foreach ($param as $item) {
        if (!(isset($item) && $item !== '')) {
            $mark = false;
        }
    }

    return $mark;

}

// 字符传前后加'\''，转为数据库查询形式
function toDatabaseStr ($str = '')
{
    $str_new = '\''.$str.'\'';

    return $str_new;

}

// 过滤传入get、post参数
function filter($para, $type = 'get', $data_type = 'string')
{
    if ($type == 'get') {
        if ($data_type == 'string') {
            $paraResult = isset($_GET[$para]) && $_GET[$para] !== '' ? $_GET[$para] : null;
        } elseif ($data_type == 'int') {
            $paraResult = isset($_GET[$para]) && $_GET[$para] !== '' ? intval($_GET[$para]) : null;
        } else {
            $paraResult = isset($_GET[$para]) && $_GET[$para] !== '' ? $_GET[$para] : null;
        }

    } elseif ($type == 'post') {
        if ($data_type == 'string') {
            $paraResult = isset($_POST[$para]) && $_POST[$para] !== '' ? $_POST[$para] : null;
        } elseif ($data_type == 'int') {
            $paraResult = isset($_POST[$para]) && $_POST[$para] !== '' ? intval($_POST[$para]) : null;
        } else {
            $paraResult = isset($_POST[$para]) && $_POST[$para] !== '' ? $_POST[$para] : null;
        }

    } else {
        die('本方法只支持get或post传值');
    }

    return $paraResult;
}

// 过滤页面中展示字段数据
function filterPage($para)
{

    if (isset($para)) {
        $paraResult = isset($para) && $para !== ''
            ? str_replace(['<', '>'], ['&#60;', '&#62;'], $para)
            : '';
        return $paraResult;
    } else {
        return '';
    }
}