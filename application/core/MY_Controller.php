<?php if (! defined('BASEPATH')) exit('No direct script access allowed');


class MY_Controller extends CI_Controller
{


    public function __construct()
    {
        parent::__construct();

        // 加载必要的函数/类库
        $this->load->helper('url');
//        $this->load->helper('security');
//        $this->load->library('security');
        $this->load->library('curl/Curl');

    }

    /**
     *
     * 统一封装API请求
     *
     */
    protected function get($url, $data = [], $headers = [], $options = [])
    {
        return $this->request('GET', $url, $data, $headers, $options);
    }

    protected function post($url, $data = [], $headers = [], $options = [])
    {
        return $this->request('POST', $url, $data, $headers, $options);
    }

    protected function request($method, $url, $data = [], $headers = [], $options = [])
    {
        $method = strtoupper($method);

        $query = parse_url($url, PHP_URL_QUERY) ?: '';
        parse_str($query, $queryArr);
        // 删除掉csrf的参数，不能传到接口，接口接受会出错.
        unset($data['ci_csrf_token']);
        unset($data['seller_web_ctn']);

//        $queryArr['no_check'] = 1;

        // 键值对, 值为null的参数, 不能参与签名
        foreach ($queryArr as $key => $val) {
            if (! isset($val)) {
                unset($queryArr[$key]);
            }
        }

        foreach ($data as $key => $val) {
            if (! isset($val)) {
                unset($data[$key]);
            }
        }

        $nounce = uniqid();
        $queryArr['nounce'] = $nounce;
        $data['nounce'] = $nounce;

        if ($method == 'GET') {
            $queryArr = array_merge($data, $queryArr);
//            $queryArr['sign'] = $this->generateSignature($queryArr);
        } elseif ($method == 'POST') {
//            $queryArr['sign'] = $this->generateSignature($queryArr);
//            $data['sign'] = $this->generateSignature($data);
        }

        $length = strpos($url, '?') ?: strlen($url);
        $url = substr($url, 0, $length);
        $url = $url . '?' . http_build_query($queryArr);

        if ($method == 'POST') {
            $headers = array_merge([
                'Content-Type' => 'application/json; charset=utf-8',
            ], $headers);
        }

        $options = array_merge([
            'timeout' => 30,
        ], $options);

        // API响应json格式(失败状态)
        $result = [
            'original' => [
                'status' => 'fail',
                'ret_code' => 200,
                'msg' => '',
            ],
        ];

        try {
            $start_run_time = explode(' ', microtime());

            if ($method == 'GET') {
                $response = Curl::get($url, $headers, $options);
            } elseif ($method == 'POST') {
                $response = Curl::post($url, $headers, json_encode($data), $options);
            } else {
                die('type类型错误');
            }

            $end_run_time = explode(' ', microtime());
            $run_time = $end_run_time[0] + $end_run_time[1] - ($start_run_time[0] + $start_run_time[1]);

            $body = $response->body;
            $data = json_decode($body, true);

            // 数据兼容性
            if (! is_array($data) || ! $data) {
                $result['original']['msg'] = '数据错误, 请稍后重试';
            } else {
                $result = $data;
            }
        } catch(Exception $e) {
            $result['original']['msg'] = '网络错误, 请稍后重试';
        }


        return $result;
    }

    // 过滤传入get、post参数
    public function filter($para, $type = 'get', $data_type = 'string')
    {
        if ($type == 'get') {
            if ($data_type == 'string') {
                $paraResult = isset($_GET[$para]) && $_GET[$para] !== '' ? $_GET[$para] : null;
            } elseif ($data_type == 'int') {
                $paraResult = isset($_GET[$para]) && $_GET[$para] !== '' ? intval($_GET[$para]) : null;
            } else {
                $paraResult = isset($_GET[$para]) && $_GET[$para] !== '' ? $_GET[$para] : null;
            }

        } elseif ($type == 'post') {
            if ($data_type == 'string') {
                $paraResult = isset($_POST[$para]) && $_POST[$para] !== '' ? $_POST[$para] : null;
            } elseif ($data_type == 'int') {
                $paraResult = isset($_POST[$para]) && $_POST[$para] !== '' ? intval($_POST[$para]) : null;
            } else {
                $paraResult = isset($_POST[$para]) && $_POST[$para] !== '' ? $_POST[$para] : null;
            }

        } else {
            die('本方法只支持get或post传值');
        }

        return $paraResult;
    }

    // 过滤页面中展示字段数据
    public function filterPage($para)
    {

        if (isset($para)) {
            if (is_string($para)) {
                $paraResult = isset($para) && $para !== ''
                    ? str_replace(['<', '>'], ['&#60;', '&#62;'], $para)
                    : '';
            } else {
                $paraResult = isset($para) && $para !== ''
                    ? $para
                    : '';
            }

            return $paraResult;
        } else {
            return '';
        }
    }

}