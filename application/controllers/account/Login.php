<?php
class Login extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('user');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 登录验证
    public function index(){

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $mobile = isset($data['mobile']) && $data['mobile'] !== '' ? $data['mobile'] : null;
        $password = isset($data['password']) && $data['password'] !== '' ? $data['password'] : null;

        $mark = via_param([$mobile, $password]);

        if ($mark) {
            $param = [
                'mobile' => $mobile,
                'password' => $password,
            ];

            $query = $this->user->auth($param);

            $database_password = isset($query['query']) && isset($query['query'][0]) && isset($query['query'][0]->password) ?
                $query['query'][0]->password : null;

            if ($database_password === $password) {
                $out_data = out_format(null, '登录成功');

            } else {
                $out_data = out_format(null, '用户名或密码错误', 'fail');

            }

        } else {
            $out_data = out_format(null, '请填写用户名及密码', 'fail');
        }

        renderJson($out_data);

    }

}
?>