<?php
class Limit_list extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('limit');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }

    // 查询抽奖数据
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

    // 查询投票数据
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

    // 更新投票数据信息
    public function update_vote_data ()
    {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $id = isset($data['id']) && $data['id'] !== '' ? intval($data['id']) : null; // 必填
        $chooseNum = isset($data['chooseNum']) && $data['chooseNum'] !== '' ? $data['chooseNum'] : null; // 选填

        $mark = via_param([$id]);

        if ($mark) {

            // 先查询当前id用户是否已经投过票了
            $ask_param = [
                'id' => $id
            ];

            $query_arr = $this->limit->get_vote_list($ask_param);
            $query = $query_arr['query'];
            $count = $query[0]->count; // 当前用户是否存在已投过票的情况

            if (isset($count) && $count !== '') {
                $out_data = out_format(null, '您已经投过票了，不可重复投票', 'fail');

            } else {
                $param = [
                    'id' => $id,
                    'chooseNum' => $chooseNum
                ];

                $query = $this->limit->update_vote_data($param);

                $out_data = out_format(null, '更新成功');

            }

        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }


}
?>