<?php
class  CalendarAuth extends CI_Model{

    function __construct ()
    {
        parent::__construct();
    }

    // 注册接口
    public function register ($param = [])
    {

        $openid = isset($param['openid']) ? toDatabaseStr($param['openid']) : 'null';
        $avatarUrl = isset($param['avatarUrl']) ? toDatabaseStr($param['avatarUrl']) : 'null';
        $city = isset($param['city']) ? toDatabaseStr($param['city']) : 'null';
        $country = isset($param['country']) ? toDatabaseStr($param['country']) : 'null';
        $gender = isset($param['gender']) ? toDatabaseStr($param['gender']) : 'null';
        $language = isset($param['language']) ? toDatabaseStr($param['language']) : 'null';
        $nickName = isset($param['nickName']) ? toDatabaseStr($param['nickName']) : 'null';
        $province = isset($param['province']) ? toDatabaseStr($param['province']) : 'null';
        $session_key = isset($param['session_key']) ? toDatabaseStr($param['session_key']) : 'null'; // 注册时的session_key，可更新
        $timeStamp = toDatabaseStr(time());
        $timeStr = toDatabaseStr(date('Y-m-d H:i:s', time()));

        $order = 'insert into user
        (id,openid,timestamp,timestr)
        values(null, '.$openid.', '.$timeStamp.', '.$timeStr.')';

        $order_detail = 'insert into user_detail
        (id,openid,avatarUrl,city,country,gender,language,nickName,province,session_key,timestamp,timestr)
        values(null, '.$openid.', '.$avatarUrl.', '.$city.', '.$country.', '.$gender.', '.$language.', '.$nickName.'
        , '.$province.', '.$session_key.', '.$timeStamp.', '.$timeStr.')';

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('calendar', TRUE);

        $query = $DB_person->query($order); // 向user表中插入数据
        $query_detail = $DB_person->query($order_detail); // 向user_detail表中插入数据

    }

    // 数据统计接口
    public function data_count ($param = [])
    {

        $type = isset($param['type']) ? toDatabaseStr($param['type']) : 'null';
        $openid = isset($param['openid']) ? toDatabaseStr($param['openid']) : 'null';
        $avatarUrl = isset($param['avatarUrl']) ? toDatabaseStr($param['avatarUrl']) : 'null';
        $city = isset($param['city']) ? toDatabaseStr($param['city']) : 'null';
        $country = isset($param['country']) ? toDatabaseStr($param['country']) : 'null';
        $gender = isset($param['gender']) ? toDatabaseStr($param['gender']) : 'null';
        $language = isset($param['language']) ? toDatabaseStr($param['language']) : 'null';
        $nickName = isset($param['nickName']) ? toDatabaseStr($param['nickName']) : 'null';
        $province = isset($param['province']) ? toDatabaseStr($param['province']) : 'null';
        $session_key = isset($param['session_key']) ? toDatabaseStr($param['session_key']) : 'null'; // 注册时的session_key，可更新
        $timeStamp = toDatabaseStr(time());
        $timeStr = toDatabaseStr(date('Y-m-d H:i:s', time()));

        $order = 'insert into user
        (id,openid,timestamp,timestr)
        values(null, '.$openid.', '.$timeStamp.', '.$timeStr.')';

        $order_detail = 'insert into data_count
        (id,type,openid,avatarUrl,city,country,gender,language,nickName,province,session_key,timestamp,timestr)
        values(null, '.$type.', '.$openid.', '.$avatarUrl.', '.$city.', '.$country.', '.$gender.', '.$language.', '.$nickName.'
        , '.$province.', '.$session_key.', '.$timeStamp.', '.$timeStr.')';

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('calendar', TRUE);

        $query = $DB_person->query($order); // 向user表中插入数据
        $query_detail = $DB_person->query($order_detail); // 向user_detail表中插入数据

    }

    // 查询user数据表
    public function get_user_list($param = [])
    {
        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('calendar', TRUE);

        $openid = isset($param['openid']) ? $param['openid'] : null;

        if (isset($openid)) {
            if (isset($openid) && $openid !== '') {
                $where['openid'] = $openid;
            }

            $DB_person->where($where);
        }

        $DB_person->from('user');

        $db = clone($DB_person);
        $total_all = $DB_person->count_all_results(); // self_library总数

        // 新查询总数后，用可从的db配置在查询真正的数据
        $this->db = $db;
        $query = $this->db->get();

        return [
            'query' => $query->result(),
            'total_all' => $total_all
        ];

    }




}
?>