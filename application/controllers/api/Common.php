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

        // 压缩大于1M图片成缩略图
        $data = array('upload_data' => $this->upload->data());
        //"is_image":true,"image_width":169,"image_height":94,
        $data['width'] = $data['upload_data']['image_width'];
        $data['height'] = $data['upload_data']['image_height'];
        $data['path'] = $data['upload_data']['full_path'];

//        var_dump($data['upload_data']['file_size']);
//        exit;
        // 超过1M的图片进行压缩，压缩成width：1500；height：1500的图片，并上传到阿里云上
        if ($data['upload_data']['file_size'] > 1 * 1024) {
            $this->load->library("image_lib");//载入图像处理类库

            $config_big_thumb['image_library'] = 'gd2';//gd2图库
            $config_big_thumb['source_image'] = $data['path'];//原图
            $config_big_thumb['new_image'] = "./uploads" . '/' . $data['upload_data']['file_name'];//大缩略图
            $config_big_thumb['quality'] = "90%";//大缩略图
            $config_big_thumb['create_thumb'] = true;//是否创建缩略图
            $config_big_thumb['maintain_ratio'] = true;
            $config_big_thumb['width'] = 1500;//缩略图宽度
            $config_big_thumb['height'] = 1500;//缩略图的高度
            $config_big_thumb['thumb_marker'] = "_1500_1500";//缩略图名字后加上 "_300_300",可以代表是一个300*300的缩略图

            $this->image_lib->initialize($config_big_thumb);
            $this->image_lib->resize();//生成big缩略图

            // 判断是否压缩图片（生成缩略图）成功
            if (!$this->image_lib->resize())
            {
                $error = [
                    'original' => [
                        'status' => 'fail',
                        'msg' => $this->image_lib->display_errors(),
                    ],
                ];

                return renderJson($error);

            }else{

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

        } else {

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