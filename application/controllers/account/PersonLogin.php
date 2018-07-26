<?php
class PersonLogin extends CI_Controller {

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

        $mobile = isset($data['mobile']) && $data['mobile'] !== '' ? $data['mobile'] : null;
        $password = isset($data['password']) && $data['password'] !== '' ? $data['password'] : null;
        $type = isset($data['type']) && $data['type'] !== '' ? $data['type'] : 1; // 1:手机号；2：邮箱

        $mark = via_param([$mobile, $password]);

        if ($mark) {
            $param = [
                'mobile' => $mobile,
                'password' => $password,
                'type' => $type
            ];

            $query = $this->personAuth->auth($param);

            $database_password = isset($query['query']) && isset($query['query'][0]) && isset($query['query'][0]->password) ?
                $query['query'][0]->password : null;

            if ($database_password === $password) {

                $dateOrigin = isset($query['query']) && isset($query['query'][0]) ?
                    json_decode($query['query'][0], true) : null;

                $data = [
                    'data' => $dateOrigin
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

    // 用户注册
    public function register()
    {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $mobile = isset($data['mobile']) && $data['mobile'] !== '' ? $data['mobile'] : null;
        $password = isset($data['password']) && $data['password'] !== '' ? $data['password'] : null;
        $allow = isset($data['allow']) && $data['allow'] !== '' ? $data['allow'] : null;

        $mark = via_param([$mobile, $password]);

        if ($mark) {
            $param = [
                'mobile' => $mobile,
                'password' => $password,
                'allow' => $allow
            ];

            // 获取公司所有人员数据
            $query = $this->limit->get_company_list($param);

            $person_mark = $query['total_all'];

            if ($person_mark) {

                $query = $this->user->auth($param);

                $database_mobile = isset($query['query']) && isset($query['query'][0]) && isset($query['query'][0]->mobile) ?
                    $query['query'][0]->mobile : null;

                if ($database_mobile == $mobile) {
                    $out_data = out_format(null, '您已经注册过，如忘记密码请联系管理员', 'fail');

                } else {
                    $query = $this->user->register($param);

                    $out_data = out_format(null, '用户注册成功');
                }

            } else {
                $out_data = out_format(null, '请用真实姓名注册或不在公司名单中，如有疑问，请联系管理员查询', 'fail');

            }


        } else {
            $out_data = out_format(null, '请填写姓名及密码', 'fail');
        }

        renderJson($out_data);

    }

    // 更新登录user表的数据
    public function update_data ()
    {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $id = isset($data['id']) ? $data['id'] : null; // 必填
        $mobile = isset($data['mobile']) && $data['mobile'] !== '' ? $data['mobile'] : null; // 选填
        $password = isset($data['password']) && $data['password'] !== '' ? $data['password'] : null; // 选填
        $choose_status = isset($data['choose_status']) && $data['choose_status'] !== '' ? $data['choose_status'] : null; // 选填
        $img_url = isset($data['img_url']) && $data['img_url'] !== '' ? $data['img_url'] : null; // 选填

        $mark = via_param([$id]);

        if ($mark) {
            $param = [
                'id' => $id,
                'mobile' => $mobile,
                'password' => $password,
                'choose_status' => $choose_status,
                'img_url' => $img_url
            ];

            $query = $this->user->update_data($param);

            $out_data = out_format(null, '更新成功');
        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }

}
?>