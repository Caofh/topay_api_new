<?php
class Person_page extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('PersonPage');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 查询personPage详情数据
    public function index(){

        header('Access-Control-Allow-Origin:*');

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

        // 筛选出所有时间戳和资源类型
        $timeArr = [];
        $typeArr = [];
        foreach ($query as $item) {
            $nowStamp = $item->timestamp;
            $nowType = $item->source_type;
            if (!in_array($nowStamp, $timeArr)) {
                array_push($timeArr,$nowStamp);
            }
            if (!in_array($nowType, $typeArr)) {
                array_push($typeArr,$nowType);
            }
        }

        // 按照时间戳整理数据
        $data_result = [];
        foreach ($timeArr as $item) {

            $obj = [];
            $obj_data = [];

            $currStamp = $item;
            foreach ($query as $item_son) {

                // 如果时间戳相等的话
                if ($item_son->timestamp === $currStamp) {
                    $obj = [
                        'timestamp' => $item_son->timestamp
                    ];
                    array_push($obj_data, $item_son);

                }

            }

            $obj_data_new = [];
            foreach ($typeArr as $item_type) {

                // item是每一个类型（每个时间戳中的）
                $obj_type = [
                    'source_type' => '',
                    'data' => []
                ];

                $obj_data_type = [];
                $currType = $item_type;
                foreach ($obj_data as $item_son_type) {

                    // 如果资源类型相等的话
                    if ($item_son_type->source_type == $currType) {
                        $obj_type['source_type'] = $item_son_type->source_type;
                        array_push($obj_data_type, $item_son_type);

                    }

                }

                $obj_type['data'] = $obj_data_type;

                if ($obj_type['source_type'] !== '' && count($obj_type['data']) ) {
                    array_push($obj_data_new, $obj_type);
                }

            }

            $obj['data'] = $obj_data_new;
            array_push($data_result, $obj);

        }

        $data = [
            'data' => $data_result,
            'total_all' => $total_all,
            'total' => count($query)
        ];

        $out_data = out_format($data);
        renderJson($out_data);

    }


    // 增加资源数据
    public function add_data ()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $user_id = isset($data['user_id']) && $data['user_id'] !== '' ? $data['user_id'] : null; // 必填
        $user_name = isset($data['user_name']) && $data['user_name'] !== '' ? $data['user_name'] : null;
        $timestamp = isset($data['timestamp']) && $data['timestamp'] !== '' ? $data['timestamp'] : null;
        $title = isset($data['title']) && $data['title'] !== '' ? $data['title'] : null;
        $source_data = isset($data['source_data']) && is_array($data['source_data']) && count($data['source_data']) ? $data['source_data'] : null;

        $mark = via_param([$user_id, $source_data]);

        if ($mark) {
            $param = [
                'user_id' => $user_id,
                'user_name' => $user_name,
                'timestamp' => $timestamp,
                'title' => $title,
                'source_data' => $source_data
            ];

            $query = $this->PersonPage->add_data($param);

            $out_data = out_format(null, '操作成功');

        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }


}
?>