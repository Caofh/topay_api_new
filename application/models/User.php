<?php
class User extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 数据表文档：https://www.showdoc.cc/tpdoc?page_id=15405988

    // 验证登录
    public function auth ($param = [])
    {
        $mobile = $param['mobile'];
//        $password = $param['password'];

        if (isset($mobile)) {
            if (isset($mobile) && $mobile !== '') {
                $where['mobile'] = $mobile;
            }

            $this->db->where($where);

        }

        $this->db->from('user');

        $query = $this->db->get();

        return [
            'query' => $query->result()
        ];

    }

}
?>