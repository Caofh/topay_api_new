<?php
class Common extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }

    // 上传图片资源方法
    public function upload_files ()
    {

//        header('Access-Control-Allow-Origin:*');

        // 取得传入数据
//        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];
//        renderJson($data['file_type']);
//        exit;

//        $fileName = $_FILES['file'];
        $targetDir = isset($_GET['file_type']) && $_GET['file_type'] !== '' ? $_GET['file_type'].'/' : '';

//        renderJson($targetDir);

        //上传类的配置
//        $config['max_size'] = '10 * 1024 * 1024'; //文件大小

        $config['upload_path'] = './uploads/'.$targetDir;
        $config['allowed_types'] = 'gif|jpg|jpeg|png|html';
//        $config['max_size'] = 10 * 1024; // 10M
        $config['max_size'] = 1.5 * 1024; // 1.5M
        $config['file_name']=uniqid(); //生成文件名
//        $config['max_width']  = '2000';
//        $config['max_height']  = '2000';

        //实列化上传类,把实例化对象赋值给控制器的同名属性
        $this->load->library('upload', $config);

        //返回结果只能是布尔值
        $status=$this->upload->do_upload('file');

        if($status) {
            renderJson([
                'status'=>true,
                'info'=>$this->upload->data(), //获取上传信息
                'target_path' => RESOURCE_URL.'/' . 'uploads' . '/' . $targetDir . $this->upload->data()['orig_name']
            ]);
        }else{
            renderJson([
                'status'=>false,
                'info'=>$this->upload->display_errors()
            ]);
        }

    }

    // 将base64文件生成二进制文件，并储存在uploads文件夹内.
    public function base64_change()
    {

        // 取得传入数据
        $data = file_get_contents("php://input") ? json_decode(file_get_contents("php://input"), true) : [];
        $image = isset($data['image']) && $data['image'] !== '' ? $data['image'] : ''; // base64的图片流
        $image_path = isset($data['path']) && $data['path'] !== '' ? $data['path'] : ''; // 上传图片路径

        if (strstr($image,",")){
            $image = explode(',',$image);
            $image = $image[1];
        }

        // 图片名字
        $imageName = "cut_".date("His",time())."_".rand(1111,9999).'.png';

        // 图片路径
        $path = './uploads/' . $image_path;
        if (!is_dir($path)){ //判断目录是否存在 不存在就创建
            mkdir($path,0777,true);
        }

        $imageSrc=  $path."/". $imageName;  //图片路径 + 图片名字

        $r = file_put_contents($imageSrc, base64_decode($image));//返回的是字节数
        if (!$r) {
            renderJson([
                'data'=>null,
                "code"=>1,
                "msg"=>"图片生成失败",
                "status" => 'fail'
            ]);
        }else{
            renderJson([
                'data'=>1,
                "code"=>0,
                "msg"=>"图片生成成功",
                'target_path' => RESOURCE_URL.'/' . 'uploads' . '/' . $image_path .'/' . $imageName,
                "status" => 'success'
            ]);
        }

    }


}
?>