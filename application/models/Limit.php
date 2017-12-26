<?php
class Limit extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 数据表文档：https://www.showdoc.cc/tpdoc?page_id=15405988

    // 查询choose_list数据表
    public function get_choose_list($param = [])
    {

        $id = isset($param['id']) ? $param['id'] : null;
        $name = isset($param['name']) ? $param['name'] : null;

        if (isset($id) || isset($name)) {
            if (isset($id) && $id !== '') {
                $where['id'] = $id;
            }
            if (isset($name) && $name !== '') {
                $where['name'] = $name;
            }

            $this->db->where($where);
        }

        $this->db->from('choose_list');

        $db = clone($this->db);
        $total_all = $this->db->count_all_results(); // self_library总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $this->db = $db;
        $query = $this->db->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }

    // 查询vote_list数据表
    public function get_vote_list($param = [])
    {

        $id = isset($param['id']) ? $param['id'] : null;
        $name = isset($param['name']) ? $param['name'] : null;

        if (isset($id) || isset($name)) {
            if (isset($id) && $id !== '') {
                $where['id'] = $id;
            }
            if (isset($name) && $name !== '') {
                $where['name'] = $name;
            }

            $this->db->where($where);
        }

        $this->db->from('vote_list');

        $db = clone($this->db);
        $total_all = $this->db->count_all_results(); // self_library总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $this->db = $db;
        $query = $this->db->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }

    // 查询player_list数据表
    public function get_player_list($param = [])
    {

        $name = isset($param['name']) ? $param['name'] : null;

        if (isset($name)) {
            if (isset($name) && $name !== '') {
                $where['name'] = $name;
            }

            $this->db->where($where);
        }

        $this->db->from('player_list');

        $db = clone($this->db);
        $total_all = $this->db->count_all_results(); // player_list数据表总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $this->db = $db;
        $query = $this->db->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }

    // 更新vote_list的投票数据表数据
    function update_vote_data ($param = [])
    {

        $id = isset($param['id']) ? $param['id'] : null;
        $chooseNum = isset($param['chooseNum']) ? toDatabaseStr($param['chooseNum']) : 'null';

        if (isset($id)) {
            if ($chooseNum && $chooseNum !== 'null') {
                $order = 'update vote_list set 
                count='.$chooseNum.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

        }
    }

    // 更新player_list的走秀数据表数据
    function update_player_data ($param = [])
    {

        $id = isset($param['id']) ? $param['id'] : null;
        $name = isset($param['name']) ? toDatabaseStr($param['name']) : 'null';
        $number = isset($param['number']) ? toDatabaseStr($param['number']) : 'null';
        $img_url = isset($param['img_url']) ? toDatabaseStr($param['img_url']) : 'null';
        $group_type = isset($param['group_type']) ? toDatabaseStr($param['group_type']) : 'null';

        if (isset($id)) {

            if ($name && $name !== 'null') {
                $order = 'update player_list set 
                name='.$name.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

            if ($number && $number !== 'null') {
                $order = 'update player_list set 
                number='.$number.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

            if ($img_url && $img_url !== 'null') {
                $order = 'update player_list set 
                img_url='.$img_url.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

            if ($group_type && $group_type !== 'null') {
                $order = 'update player_list set 
                group_type='.$group_type.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

        }
    }

}
?>