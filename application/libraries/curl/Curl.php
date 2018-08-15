<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    require_once 'library/Requests.php';

    class Curl extends Requests
    {

        public function __construct()
        {
            self::register_autoloader();
        }

        public static function get($url, $headers = [], $options = [])
        {
            return parent::get($url, $headers, $options);
        }

        public static function post($url, $headers = [], $data = [], $options = [])
        {
            return parent::post($url, $headers, $data, $options);
        }

    }