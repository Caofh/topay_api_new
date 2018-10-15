<?php
class Dream_page extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }

    // 用户列表接口(post传参)
    public function get_data () {
        // 处理传参
        $url = isset($_GET['url']) && $_GET['url'] !== '' ? $_GET['url'] : null; // 必填
//        $type = isset($_GET['type']) && $_GET['type'] !== '' ? $_GET['type'] : 'get'; // 默认是get

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : null; // post传入数据
        $url = $url . $data['get']; // 将get参数加上
        $method = $data['method']; // 将get参数加上
        $headers = [
            'content-type' => 'application/x-www-form-urlencoded'
        ];
        unset($data['get']);
        unset($data['method']);

        if ($method == 'post') {
            if ($url) {
                $data = $this->post($url, $data, $headers);
                renderJson($data);

            } else {
                $out_data = out_format(null, '参数有误', 'fail');
                renderJson($out_data);

            }

        } else {
            if ($url) {
                $data = $this->get($url, null, $headers);
                renderJson($data);

            } else {
                $out_data = out_format(null, '参数有误', 'fail');
                renderJson($out_data);

            }

        }


    }

}
?>