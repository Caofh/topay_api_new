<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


//获取客户端的IP地址
if( ! function_exists("get_client_ip")){
	function get_client_ip(){
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")){
			$ip = getenv("HTTP_CLIENT_IP");
		}else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")){
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		}else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			$ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = "unknown";
		return($ip);
	}
}

//数组转换为一维数组
if( ! function_exists("arrayChange")){
	
	function arrayChange($str){
		static $arr2;
		foreach($str as $v){
			if(is_array($v)){
				$this->arrayChange($v);
			}else{
				$arr2[]=$v;
			}
		}
		return $arr2;
	}
}

/**
 * 格式化文件大小
 * @param int $byte
 */
function formatSize($byte, $float = 1){
	$units = array('B', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
	$unitindex = 0;

	while($byte >= 1024) {
		$byte /= 1024;
		++$unitindex;
	}
	if(is_int($byte)){
		return $byte.' '.$units[$unitindex];
	}
	else{
		return round($byte, $float).' '.$units[$unitindex];
	}
}

/**
 
 * 处理form 提交的参数过滤
 * $string	string  需要处理的字符串或者数组
 * $force	boolean  强制进行处理
 * @return	string 返回处理之后的字符串或者数组
 */
if(!function_exists("daddslashes")){
	function daddslashes($string, $force = 1) {
		if(is_array($string)) {
			$keys = array_keys($string);
			foreach($keys as $key) {
				$val = $string[$key];
				unset($string[$key]);
				$string[addslashes($key)] = daddslashes($val, $force);
			}
		} else {
			$string = addslashes($string);
		}
		return $string;
	}
}

/*
32	函数名称：verify_id()
33	函数作用：校验提交的ID类值是否合法
34	参　　数：$id: 提交的ID值
35	返 回 值：返回处理后的ID
36	
*/
if( !function_exists("verify_id") ){
	function verify_id($id=null) {
		if (!$id) { 
			return 0;
		} // 是否为空判断
		elseif (inject_check($id)) { 
			return 0;
		} // 注射判断
		elseif (!is_numeric($id)) { 
			return 0 ;			
		} // 数字判断
		$id = intval($id); // 整型化		 
		return $id;
	}
}

/*
 *检测提交的值是不是含有SQL注射的字符，防止注射，保护服务器安全
 *参　　数：$sql_str: 提交的变量
 *返 回 值：返回检测结果，ture or false 
 */

if( !function_exists("inject_check") ){
	function inject_check($sql_str) {
		return @eregi('select|insert|and|or|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile', $sql_str); // 进行过滤
	}
}

/**
 *  处理禁用HTML但允许换行的内容
 *
 * @access    public
 * @param     string  $msg  需要过滤的内容
 * @return    string
 */
if ( ! function_exists('TrimMsg')) {
    function TrimMsg($msg)
    {
        $msg = trim(stripslashes($msg));
        $msg = nl2br(htmlspecialchars($msg));
        $msg = str_replace("  ","&nbsp;&nbsp;",$msg);
        return addslashes($msg);
    }
}

/**
 * 切割字符串(按照页面显示的中文数，一个中文=2个字符)
 * @param string $text
 * @param int $length
 */
function cutString($text, $length = 18, $dot = '...') {
	$length *= 2;
	$mb_length = mb_strlen($text, 'UTF-8');
	$new_text = '';
	//遍历每个字符
	for($i=0, $new_length=0; $i<$mb_length; $i++){
		$ch = mb_substr($text, $i, 1, 'UTF-8');
		$ch_len = mb_strwidth($ch, 'UTF-8');
		if(preg_match("/^[\x7f-\xff]+$/", $ch)){
			$new_length += 2;
		}else{
			if($ch_len == 1){
				//大写字母占字符宽度
				if(ctype_upper($ch) || ctype_digit($ch)){
					$new_length += 1.5;
				}
				else{
					$new_length += 1;
				}
			}
			else{
				$new_length += 2;
			}
		}
		if($new_length > $length){
			break;
		}
		$new_text .= $ch;
	}
	if($new_length > $length){
		$new_text .= $dot;
	}
	return $new_text;
}

function html_clean_xss($str) {
	return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * 输出json数据
 * @param mixed $data
 * @param boolean $formated 数据是否已经使用json_encode格式化
 */
function renderJson($data, $formated = true){
	header('Content-type: application/json');
	if(is_string($data) && $formated){
		echo $data;
	}
	else{
		echo json_encode($data);
	}
	exit;
}

function encodePathForUrl($path) {
    //path一定要是utf-8编码
    $convert_encodings = array('UTF-8', 'GBK', 'GB2312', 'ISO-8859-1', 'BIG5');
    $encoding = mb_detect_encoding($path, $convert_encodings);
    if($encoding != 'UTF-8'){
        $path = mb_convert_encoding($path, 'UTF-8', $encoding);
    }
    $parts = explode('/', $path);
    $parts = array_map('rawurlencode', $parts);

    return implode('/', $parts);
}

/**
 * 获取分页信息
 * @param int $page 页码
 * @param int $pagesize 页大小
 * @param int $total 总数
 */
function getPageInfo($page, $pagesize, $total) {
    $page = intval($page);
    $pagesize = intval($pagesize);

    if (empty($page)) {
        $page = 1;
    }
    if (empty($pagesize)) {
        $pagesize = 20;
    }

    $pageinfo = array();

    $pageinfo['rsTotal'] = $total;
    $pageinfo['pageSize'] = $pagesize;
    $pageinfo['pageTotal'] = ceil($pageinfo['rsTotal'] / $pageinfo['pageSize']);
    $pageinfo['page'] = max(1, min($page, $pageinfo['pageTotal']));
    $pageinfo['start'] = ($pageinfo['page'] -1 ) * $pageinfo['pageSize'];

    //有上一页
    if ($pageinfo['page'] > 1) {
        $pageinfo['preStart'] = ($pageinfo['page'] - 2) * $pageinfo['pageSize'];
    }

    //有下一页
    if ($pageinfo['page'] < $pageinfo['pageTotal']) {
        $pageinfo['nextStart'] = $pageinfo['page'] * $pageinfo['pageSize'];
    }

    return $pageinfo;
}

/**
 * 获取完整的图片路径，又拍云CDN
 * @param string $img_uri 绝对、相对路径
 * @param string $size [optional] 可选缩率图大小，默认原图
 * @return string 完整的缩率图路径
 */
function cdn_image($img_uri, $size = '')
{
    $path = preg_replace('#^((https|http):)?\/\/.*?\/#', '/', $img_uri);

    $domain = IMG_URL;

    return $domain.$path.$size;
}

/**
 * 可编码、可解码方法，如下示例：

$seller_uid = self::$uid; // 需要编码的数据
$key = 'ofashion-community'; // 编解码规则

$authcode = authcode($seller_uid, 'ENCODE', $key,0); // 编码
$authcode = authcode($seller_uid, 'DECODE', $key,0); // 解码
 */
function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
    $ckey_length = 4;

    // 密匙
    $key = md5($key ? $key : $GLOBALS['discuz_auth_key']);

    // 密匙a会参与加解密
    $keya = md5(substr($key, 0, 16));
    // 密匙b会用来做数据完整性验证
    $keyb = md5(substr($key, 16, 16));
    // 密匙c用于变化生成的密文
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length):
        substr(md5(microtime()), -$ckey_length)) : '';
    // 参与运算的密匙
    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);
    // 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，
    //解密时会通过这个密匙验证数据完整性
    // 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) :
        sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);
    $result = '';
    $box = range(0, 255);
    $rndkey = array();
    // 产生密匙簿
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }
    // 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }
    // 核心加解密部分
    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        // 从密匙簿得出密匙进行异或，再转成字符
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }
    if($operation == 'DECODE') {
        // 验证数据有效性，请看未加密明文的格式
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) &&
            substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        // 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
        // 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
        return $keyc.str_replace('=', '', base64_encode($result));
    }
}