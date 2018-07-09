<?php
class Person_page extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('PersonPage');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 查询首页列表数据
    public function index(){

        // 处理传参
        $id = isset($_GET['user_id']) && $_GET['user_id'] !== '' ? $_GET['user_id'] : null; // 必填
        $start = isset($_GET['start']) && $_GET['start'] !== '' ? intval($_GET['start']) : 0; // 偏移起始
        $count = isset($_GET['count']) && $_GET['count'] !== '' ? intval($_GET['count']) : 20; // 偏移数量

        $param = [
            'id' => $id,
            'start' => $start,
            'count' => $count
        ];

        $query_arr = $this->PersonPage->get_list($param);
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



}
?>