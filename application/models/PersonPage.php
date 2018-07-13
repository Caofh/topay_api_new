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

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('personPage', TRUE);

        if (isset($id)) {
            if (isset($id) && $id !== '') {
                $where['id'] = $id;
            }

            $DB_person->where($where);
        }

        $DB_person->from('person_show_list');

        $db = clone($DB_person);
        $total_all = $DB_person->count_all_results(); // self_library总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $DB_person = $db;
        if(intval($count) >= 0 ) {
            $DB_person->limit($count, $start);
        }
        $query = $DB_person->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }


}
?>