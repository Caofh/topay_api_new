<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

// URL
define('RESOURCE_URL', 'https://tpdoc.cn'); // 项目内上传的资源路径

//define('THUMBNAIL_SUFFIX_200', '!w200'); // 缩略图后缀: !w200
//define('THUMBNAIL_SUFFIX_320', '!w320'); // 缩略图后缀: !w320
//define('THUMBNAIL_SUFFIX_640', '!w640'); // 缩略图后缀: !w640
define('THUMBNAIL_SUFFIX_200', '?x-oss-process=image/resize,w_200/format,png'); // 缩略图后缀: !w200
define('THUMBNAIL_SUFFIX_320', '?x-oss-process=image/resize,w_320/format,png'); // 缩略图后缀: !w320
define('THUMBNAIL_SUFFIX_640', '?x-oss-process=image/resize,w_640/format,png'); // 缩略图后缀: !w640

// SeasLog配置
define('SEASLOG_ENABLE', true);
define('SEASLOG_BASE_PATH', '/var/log/inner_web'); // 目录路径
define('SEASLOG_SUB_DIR', 'seller'); // 子目录

// Session Path
define('SESSION_PATH', '/var/log/inner_web_session/seller');
