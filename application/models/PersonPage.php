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

    // 插入数据表数据
    public function add_data ($param = [])
    {

        $user_id = isset($param['user_id']) ? toDatabaseStr($param['user_id']) : 'null';
        $user_name = isset($param['user_name']) ? toDatabaseStr($param['user_name']) : 'null';
        $timestamp = isset($param['timestamp']) ? toDatabaseStr($param['timestamp']) : 'null';
        $title = isset($param['title']) ? toDatabaseStr($param['title']) : 'null';
        $source_data = isset($param['source_data']) && is_array($param['source_data']) && count($param['source_data']) ?
            $param['source_data'] : null;


        // 组合数据库语言（插入多条数据）
        $db_value = '';
        foreach ($source_data as $key => $item) {

            $item['source_content'] = isset($item['source_content']) && $item['source_content'] !== '' ? toDatabaseStr($item['source_content']) : 'null';
            $item['source_title'] = isset($item['source_title']) && $item['source_title'] !== '' ? toDatabaseStr($item['source_title']) : 'null';
            $item['source_img_url'] = isset($item['source_img_url']) && $item['source_img_url'] !== '' ? toDatabaseStr($item['source_img_url']) : 'null';
            $item['$source_data'] = isset($item['$source_data']) && $item['$source_data'] !== '' ? toDatabaseStr($item['$source_data']) : 'null';

            $db_value .= '(null, '.$user_id.', '.$user_name.', '.$timestamp.', '.$title.', '.$item['source_type'].', '
            .$item['source_content'].', '.$item['source_title'].', '.$item['source_img_url'].')'.($key != (count($source_data) - 1) ? ',' : '');
        }

        $order = 'insert into person_show_list
        (id,user_id,user_name,timestamp,title,source_type,source_content,source_title,source_img_url)
        values'.$db_value;


        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('personPage', TRUE);
        $query = $DB_person->query($order);

    }

    // 插入基本信息数据
    public function add_base_info ($param = [])
    {

        $phone = isset($param['phone']) ? toDatabaseStr($param['phone']) : 'null';
        $email = isset($param['email']) ? toDatabaseStr($param['email']) : 'null';
        $nickname = isset($param['nickname']) ? toDatabaseStr($param['nickname']) : 'null';
        $sex = isset($param['sex']) ? toDatabaseStr($param['sex']) : 'null';
        $birthday = isset($param['birthday']) ? toDatabaseStr($param['birthday']) : 'null';
        $password = isset($param['password']) ? toDatabaseStr($param['password']) : 'null';
        $confirmPassword = isset($param['confirmPassword']) ? toDatabaseStr($param['confirmPassword']) : 'null';
        $selfWord = isset($param['selfWord']) ? toDatabaseStr($param['selfWord']) : 'null';
        $uploadImgPath = isset($param['uploadImgPath']) ? toDatabaseStr($param['uploadImgPath']) : 'null';

        $order = 'insert into person_base_info
        (id,phone,email,nickname,sex,birthday,password,confirmPassword,selfWord,uploadImgPath)
        values(null, '.$phone.', '.$email.', '.$nickname.', '.$sex.', '.$birthday.', '.$password.'
        , '.$confirmPassword.', '.$selfWord.', '.$uploadImgPath.')';

        // 手动切换本地的personpage数据库
        $DB_person = $this->load->database('personPage', TRUE);
        $query = $DB_person->query($order);

    }


}
?>