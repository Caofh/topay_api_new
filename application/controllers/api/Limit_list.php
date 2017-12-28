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

    // 增加投票数据信息接口
    public function add_vote_data ()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $name = isset($data['name']) && $data['name'] !== '' ? $data['name'] : null; // 必填
        $count = isset($data['count']) && $data['count'] !== '' ? $data['count'] : null;

        $mark = via_param([$name, $count]);

        if ($mark) {
            $param = [
                'name' => $name,
                'count' => $count
            ];

            $query = $this->limit->add_vote_data($param);

            $out_data = out_format(null, '操作成功');

        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }

    // 查询参加走秀选手的数据数据
    public function player_list(){

        // 处理传参
        $name = isset($_GET['name']) && $_GET['name'] !== '' ? $_GET['name'] : null; // 选填

        $param = [
            'name' => $name
        ];

        $query_arr = $this->limit->get_player_list($param);
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

    // 更新参加走秀选手信息
    public function update_player_data ()
    {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $id = isset($data['id']) && $data['id'] !== '' ? intval($data['id']) : null; // 必填
        $name = isset($data['name']) && $data['name'] !== '' ? $data['name'] : null; // 选填
        $number = isset($data['number']) && $data['number'] !== '' ? $data['number'] : null; // 选填
        $img_url = isset($data['img_url']) && $data['img_url'] !== '' ? $data['img_url'] : null; // 选填
        $group_type = isset($data['group_type']) && $data['group_type'] !== '' ? $data['group_type'] : null; // 选填

        $mark = via_param([$id]);

        if ($mark) {

            $param = [
                'id' => $id,
                'name' => $name,
                'number' => $number,
                'img_url' => $img_url,
                'group_type' => $group_type,
            ];

            $query = $this->limit->update_player_data($param);

            $out_data = out_format(null, '更新成功');


        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }

    // 更新允许投票接口信息
    public function update_allow_data ()
    {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $type = isset($data['type']) && $data['type'] !== '' ? intval($data['type']) : null; // 必填
        $allow = isset($data['allow']) && $data['allow'] !== '' ? $data['allow'] : null; // 选填

        $mark = via_param([$type]);

        if ($mark) {

            $param = [
                'type' => $type,
                'allow' => $allow
            ];

            $query = $this->limit->update_allow_data($param);

            $out_data = out_format(null, '更新成功');


        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }


}
?>