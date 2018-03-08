<?php
class Wx_auth extends CI_Controller {

    function __construct()
    {
        parent::__construct();

        $this->load->model('content');

        date_default_timezone_set('PRC'); // 将区时设为北京时区
    }


    // 查询首页文章列表
    public function index(){

        // 处理传参
        $signature = isset($_GET['signature']) && $_GET['signature'] !== '' ? $_GET['signature'] : null; //
        $timestamp = isset($_GET['timestamp']) && $_GET['timestamp'] !== '' ? $_GET['timestamp'] : null; //
        $nonce = isset($_GET['nonce']) && $_GET['nonce'] !== '' ? $_GET['nonce'] : null; //
        $echostr = isset($_GET['echostr']) && $_GET['echostr'] !== '' ? $_GET['echostr'] : null; //

        $param = [
            'signature' => $signature,
            'timestamp' => $timestamp,
            'nonce' => $nonce,
            'echostr' => $echostr
        ];

        return $param['echostr'];

    }

}
?>