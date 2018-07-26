<?php
class PersonAuth extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 验证登录接口
    public function auth ($param = [])
    {
        $mobile = $param['mobile'];
        $password = $param['type'] ? $param['type'] : 1;

        if (isset($mobile)) {
            if (isset($mobile) && $mobile !== '') {
                if ($password == 1) {
                    $where['phone'] = $mobile;
                } else {
                    $where['email'] = $mobile;
                }

            }

            $this->db->where($where);

        }

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('personPage', TRUE);

        $DB_person->from('person_base_info');
        $query = $DB_person->get();

        return [
            'query' => $query->result()
        ];

    }


}
?>