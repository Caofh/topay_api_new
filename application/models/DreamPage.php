<?php
class  DreamPage extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }


    // 查询数据表
    public function get_data_12($param = [])
    {
        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('dream', TRUE);

        $id = $param['id'];
        $cid = $param['cid'];
        $start = $param['start'];
        $count = $param['count'];

        if (isset($id) || $id !== '') {
            if (isset($id) && $id !== '') {
                $where['id'] = $id;
            }
            if (isset($cid) && $cid !== '') {
                $where['cid'] = $cid;
            }

            $DB_person->where($where);
        }

        $DB_person->from('Analysis_12');

        $db = clone($DB_person);
        $total_all = $DB_person->count_all_results(); // self_library总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $DB_person = $db;
        if(intval($count) >= 0 ) {
            $DB_person->limit($count, $start);
            $DB_person->order_by("id", "desc"); // 倒序排列
        }
        $query = $DB_person->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }













    // 数爬虫数据
    public function in_data ($param = [])
    {

        $cid = isset($param['cid']) ? toDatabaseStr($param['cid']) : 'null';
        $content = isset($param['content']) ? toDatabaseStr($param['content']) : 'null';
//        $content = isset($param['content']) ? toDatabaseStr(123) : 'null';
        $create_time = isset($param['create_time']) ? toDatabaseStr($param['create_time']) : 'null';
        $img_url = isset($param['img_url']) ? toDatabaseStr($param['img_url']) : 'null';
        $tid = isset($param['tid']) ? toDatabaseStr($param['tid']) : 'null';
        $title = isset($param['title']) ? toDatabaseStr($param['title']) : 'null';

        $order = 'insert into Analysis_12
        (id,cid,content,create_time,img_url,tid,title)
        values(null, '.$cid.', '.$content.', '.$create_time.', '.$img_url.', '.$tid.', '.$title.')';

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('dream', TRUE);

        $query = $DB_person->query($order); // 向data_count表中插入数据

    }




}
?>