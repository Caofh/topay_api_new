<?php
class CalendarLogin extends MY_Controller {

    // 黑名单用户，不统计数据.
    public static $blackList = [
        'odI565Y6MEyE_6mqvcNdhWC_-8WE' // Topay用户不参加数据统计，无意义
    ];

    function __construct()
    {
        parent::__construct();

        $this->load->model('calendarAuth');

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

    // 用户注册
    public function register()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];
        $openid = isset($_GET['openid']) && $_GET['openid'] !== '' ? $_GET['openid'] : null;

        // 向data中添加openid，避免get传过来没有取到问题
        $data['openid'] = $data['openid'] ? $data['openid'] : $openid;

        if (isset($openid) || isset($data['openid'])) {
            $param = $data;

            // 获取公司所有人员数据
            $query = $this->calendarAuth->get_user_list($param);

            $person_mark = $query['total_all'];

            if ($person_mark) {
                $out_data = out_format($query, '当前用户已经注册过');

            } else {
                $query = $this->calendarAuth->register($param);
                $out_data = out_format(null, '用户注册成功');

            }


        } else {
            $out_data = out_format(null, '请填写姓名及密码', 'fail');
        }

        renderJson($out_data);

    }

    // 数据统计接口(逐条插入，累加)
    public function data_count()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];
        $openid = isset($_GET['openid']) && $_GET['openid'] !== '' ? $_GET['openid'] : null;

        // 向data中添加openid，避免get传过来没有取到问题
        $data['openid'] = $data['openid'] ? $data['openid'] : $openid;

        $param = $data;

        // 黑名单中的用户不统计数据.
        if (in_array($param['openid'], self::$blackList)) {
            $out_data = out_format(null, '当前用户为开发者，不参与统计', 'fail');
            renderJson($out_data);
            return false;
        }

        $query = $this->calendarAuth->data_count($param);
        $out_data = out_format(null, '插入统计数据成功');

        renderJson($out_data);

    }

}
?>