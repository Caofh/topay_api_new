<?php
class Data_list extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('content');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 查询首页文章列表
    public function index(){

        // 处理传参
        $id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null; // 选填
        $type = isset($_GET['type']) && $_GET['type'] !== '' ? $_GET['type'] : null; // 选填
        $start = isset($_GET['start']) && $_GET['start'] !== '' ? intval($_GET['start']) : 0; // 偏移起始
        $count = isset($_GET['count']) && $_GET['count'] !== '' ? intval($_GET['count']) : 20; // 偏移数量

        $param = [
            'id' => $id,
            'type' => $type,
            'start' => $start,
            'count' => $count
        ];

        $query_arr = $this->content->get_list($param);
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

    // 删除文章接口
    public function delete_data ()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $id = isset($data['id']) && $data['id'] !== '' ? $data['id'] : null; // 必填

        $mark = via_param([$id]);

        if ($mark) {
            $param = [
                'id' => $id
            ];

            $query = $this->content->delete_data($param);

            $out_data = out_format(null, '删除成功');

        } else {
            $out_data = out_format(null, '参数id有误', 'fail');
        }

        renderJson($out_data);

    }

    // 增加文章接口
    public function add_data ()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $name = isset($data['name']) && $data['name'] !== '' ? $data['name'] : null; // 必填
        $complete = isset($data['complete']) && $data['complete'] !== '' ? intval($data['complete']) : null; // 必填
        $type = isset($data['type']) && $data['type'] !== '' ? intval($data['type']) : null; // 必填
        $content_uri = isset($data['content_uri']) && $data['content_uri'] !== '' ? $data['content_uri'] : null;
        $content_url = isset($data['content_url']) && $data['content_url'] !== '' ? $data['content_url'] : null;
        $logo_uri = isset($data['logo_uri']) && $data['logo_uri'] !== '' ? $data['logo_uri'] : null;
        $logo_url = isset($data['logo_url']) && $data['logo_url'] !== '' ? $data['logo_url'] : null;
        $create_time = date("y-m-d H:i:s");
        $update_time = date("y-m-d H:i:s");
        $bg_color = isset($data['bg_color']) && $data['bg_color'] !== '' ? $data['bg_color'] : null;

        $mark = via_param([$name, $complete, $type]);

        if ($mark) {
            $param = [
                'name' => $name,
                'content_uri' => $content_uri,
                'content_url' => $content_url,
                'logo_uri' => $logo_uri,
                'logo_url' => $logo_url,
                'create_time' => $create_time,
                'update_time' => $update_time,
                'bg_color' => $bg_color,
                'complete' => $complete,
                'type' => $type
            ];

            $query = $this->content->add_data($param);

            $out_data = out_format(null, '操作成功');

        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }

    // 更新文章信息
    public function update_data ()
    {
        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];

        $id = isset($data['id']) && $data['id'] !== '' ? intval($data['id']) : null; // 必填
        $name = isset($data['name']) && $data['name'] !== '' ? $data['name'] : null; // 必填
        $complete = isset($data['complete']) && $data['complete'] !== '' ? intval($data['complete']) : null; // 必填
        $type = isset($data['type']) && $data['type'] !== '' ? intval($data['type']) : null; // 必填
        $content_uri = isset($data['content_uri']) && $data['content_uri'] !== '' ? $data['content_uri'] : null;
        $content_url = isset($data['content_url']) && $data['content_url'] !== '' ? $data['content_url'] : null;
        $logo_uri = isset($data['logo_uri']) && $data['logo_uri'] !== '' ? $data['logo_uri'] : null;
        $logo_url = isset($data['logo_url']) && $data['logo_url'] !== '' ? $data['logo_url'] : null;
        $create_time = date("y-m-d H:i:s");
        $update_time = date("y-m-d H:i:s");
        $bg_color = isset($data['bg_color']) && $data['bg_color'] !== '' ? $data['bg_color'] : null;

        $mark = via_param([$id, $complete, $type]);

        if ($mark) {
            $param = [
                'id' => $id,
                'name' => $name,
                'content_uri' => $content_uri,
                'content_url' => $content_url,
                'logo_uri' => $logo_uri,
                'logo_url' => $logo_url,
//                'create_time' => $create_time,
                'update_time' => $update_time,
                'bg_color' => $bg_color,
                'complete' => $complete,
                'type' => $type
            ];

            $query = $this->content->update_data($param);

            $out_data = out_format(null, '更新成功');
        } else {
            $out_data = out_format(null, '参数有误', 'fail');
        }

        renderJson($out_data);

    }

    // 获取用户头像列表接口
    public function get_avator_list(){

        // 处理传参
        $id = isset($_GET['id']) && $_GET['id'] !== '' ? $_GET['id'] : null; // 选填

        $param = [
            'id' => $id
        ];

        $query_arr = $this->content->get_avator_list($param);
        $query = $query_arr['query'];
        $total_all = $query_arr['total_all'];

        $data = [
            'data' => $query,
            'total_all' => $total_all
        ];

        $out_data = out_format($data);

        renderJson($out_data);

    }


}
?>