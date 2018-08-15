<?php
class PersonLogin extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('personAuth');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 登录验证
    public function index()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $token = isset($data['token']) && $data['token'] !== '' ? $data['token'] : null;

        if ($token) {
            // 将token使用通用规则解码出参数.
            $param_data = $token = authcode($token, 'DECODE', 'person_show', 0);
            $targetArr = explode('/',$param_data); // 字符串分割

            $phone = isset($targetArr[0]) && $targetArr[0] !== '' ? $targetArr[0] : null;
            $password = isset($targetArr[1]) && $targetArr[1] !== '' ? $targetArr[1] : null;
            $type = isset($targetArr[2]) && $targetArr[2] !== '' ? $targetArr[2] : 1; // 1:手机号；2：邮箱

        } else {
            $phone = isset($data['phone']) && $data['phone'] !== '' ? $data['phone'] : null;
            $password = isset($data['password']) && $data['password'] !== '' ? $data['password'] : null;
            $type = isset($data['type']) && $data['type'] !== '' ? $data['type'] : 1; // 1:手机号；2：邮箱
        }

        $mark = via_param([$phone, $password]);

        if ($mark) {
            $param = [
                'phone' => $phone,
                'password' => $password,
                'type' => $type
            ];

            $query = $this->personAuth->auth($param);

            $database_password = isset($query['query']) && isset($query['query'][0]) && isset($query['query'][0]->password) ?
                $query['query'][0]->password : null;

            if ($database_password === $password) {

                $dataOrigin = isset($query['query']) ?  (array)$query['query'] : null;

                $auth_str = $phone.'/'.$password.'/'.$type;
                $token = authcode($auth_str, 'ENCODE', 'person_show', 0);

                $data = [
                    'data' => $dataOrigin,
                    'token' => $token
                ];
                $out_data = out_format($data, '登录成功');

            } else {
                $out_data = out_format(null, '用户名或密码错误', 'fail');

            }

        } else {
            $out_data = out_format(null, '请填写用户名及密码', 'fail');
        }

        renderJson($out_data);

    }




    // 微信登录验证
    public function wx_login () {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $appid = 'wxdda4fb53dfc35bd7';
        $secret = 'c94ab3f69a02220bef158c6d9d29126a';
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