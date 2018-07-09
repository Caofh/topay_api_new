<?php
class PersonPage extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 数据表文档：https://www.showdoc.cc/tpdoc?page_id=15405988

    // 查询数据表
    public function get_list($param = [])
    {

        $id = $param['id'];
        $start = $param['start'];
        $count = $param['count'];

        if (isset($id)) {
            if (isset($id) && $id !== '') {
                $where['id'] = $id;
            }

            $this->db->where($where);
        }

        $this->db->from('person_show_list');

        $db = clone($this->db);
        $total_all = $this->db->count_all_results(); // self_library总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $this->db = $db;
        if(intval($count) >= 0 ) {
            $this->db->limit($count, $start);
        }
        $query = $this->db->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }


}
?>