<?php
class Limit_list extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('limit');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }

    // 查询首页文章列表
    public function choose_list(){

        // 处理传参
        $id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null; // 选填
        $name = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null; // 选填

        $param = [
            'id' => $id,
            'name' => $name
        ];

        $query_arr = $this->limit->get_choose_list($param);
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

    // 查询首页文章列表
    public function vote_list(){

        // 处理传参
        $id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null; // 选填
        $name = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null; // 选填

        $param = [
            'id' => $id,
            'name' => $name
        ];

        $query_arr = $this->limit->get_vote_list($param);
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