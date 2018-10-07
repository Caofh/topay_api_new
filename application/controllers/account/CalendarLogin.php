<?php
class CalendarLogin extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 微信登录验证
    public function wx_login () {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $appid = 'wxb5c04f5d73d4db5c';
        $secret = '53ed66e4487fcaa9ee75207daf2397ef';
        $js_code = $data['code'];
        $grant_type = 'authorization_code';

        $params = [
            'appid' => $appid,
            'secret' => $secret,
            'js_code' => $js_code,
            'grant_type' => $grant_type,
        ];


        //获取数据
        $url = 'https://api.weixin.qq.com/sns/jscode2session';
        $data = $this->get($url, $params);

        if (isset($data['errcode'])) {
            $out_data = out_format($data, $data['errmsg'], 'fail');

        } else {
            $out_data = out_format($data, '登录成功');

        }


        renderJson($out_data);

    }

}
?>