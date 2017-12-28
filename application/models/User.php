<?php
class User extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 数据表文档：https://www.showdoc.cc/tpdoc?page_id=15405988

    // 验证登录接口
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

    // 注册接口
    public function register ($param = [])
    {

        $mobile = isset($param['mobile']) ? toDatabaseStr($param['mobile']) : 'null';
        $password = isset($param['password']) ? toDatabaseStr($param['password']) : 'null';
        $allow = isset($param['allow']) ? toDatabaseStr($param['allow']) : 'null';

        $order = 'insert into user
        (id,mobile,password,allow)
        values(null, '.$mobile.', '.$password.', '.$allow.')';

        $query = $this->db->query($order);

    }

    // 更新用户表接口
    function update_data ($param = [])
    {

        $id = isset($param['id']) ? $param['id'] : null;
        $mobile = isset($param['mobile']) ? toDatabaseStr($param['mobile']) : 'null';
        $password = isset($param['password']) ? toDatabaseStr($param['password']) : 'null';
        $choose_status = isset($param['choose_status']) ? toDatabaseStr($param['choose_status']) : 'null';
        $img_url = isset($param['img_url']) ? toDatabaseStr($param['img_url']) : 'null';

        if (isset($id)) {
            if ($mobile && $mobile !== 'null') {
                $order = 'update user set 
                mobile='.$mobile.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

            if ($password && $password !== 'null') {
                $order = 'update user set 
                password='.$password.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

            if ($choose_status && $choose_status !== 'null') {
                $order = 'update user set 
                choose_status='.$choose_status.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

            if ($img_url && $img_url !== 'null') {
                $order = 'update user set 
                img_url='.$img_url.'
                where id='.$id.'';

                $query = $this->db->query($order);
            }

        }
    }

}
?>