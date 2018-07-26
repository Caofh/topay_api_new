<?php
class PersonAuth extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 验证登录接口
    public function auth ($param = [])
    {
        $phone = $param['phone'];
//        $password = $param['password'];
        $type = isset($param['type']) ? $param['type'] : 1;

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('personPage', TRUE);

        if (isset($phone)) {
            if (isset($phone) && $phone !== '') {
                if ($type == 1) {
                    $where['phone'] = $phone;
                } else {
                    $where['email'] = $phone;
                }

            }

            $DB_person->where($where);

        }

        $DB_person->from('person_base_info');
        $query = $DB_person->get();

        return [
            'query' => $query->result()
        ];

    }


}
?>