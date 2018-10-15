<?php
class Dream_page extends MY_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('dreamPage');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 查询12星座分析文章
    public function get_data_12 (){

        // 处理传参
        $id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null; // 选填
        $cid = isset($_GET['cid']) && $_GET['cid'] !== '' ? $_GET['cid'] : null; // 文章类型（21：12星座性格；22：各种深度分析）
        $start = isset($_GET['start']) && $_GET['start'] !== '' ? intval($_GET['start']) : 0; // 偏移起始
        $count = isset($_GET['count']) && $_GET['count'] !== '' ? intval($_GET['count']) : 20; // 偏移数量

        $param = [
            'id' => $id,
            'cid' => $cid,
            'start' => $start,
            'count' => $count
        ];

        $query_arr = $this->dreamPage->get_data_12($param);
        $query = $query_arr['query'];
        $total_all = $query_arr['total_all'];

        $data = [
            'data' => $query,
            'total_all' => $total_all,
            'total' => count($query)
        ];

        $out_data = out_format($data);

        renderJson($out_data);

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
//        renderJson($data);

        unset($data['get']);
        unset($data['method']);

        if ($method == 'post') {
            if ($url) {
                $data = $this->post($url, $data);
                renderJson($data);

            } else {
                $out_data = out_format(null, '参数有误', 'fail');
                renderJson($out_data);

            }

        } else {
            if ($url) {
                $data = $this->get($url, null);
                renderJson($data);

            } else {
                $out_data = out_format(null, '参数有误', 'fail');
                renderJson($out_data);

            }

        }


    }


    // 插入数据接口(爬虫存数据)
    public function in_data () {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $param = $data;

        $query = $this->dreamPage->in_data($param);
        $out_data = out_format(null, '插入统计数据成功');

        renderJson($out_data);


    }



}
?>