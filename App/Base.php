<?php

namespace Xui\App;

class Base {
    protected $hostname;
    protected $cookie;
    protected $id;
    protected $methods = [
        'login'        => "login",
        'inbounds'     => "panel/api/inbounds/list",
        'inbound'      => "panel/api/inbounds/get/{id}",
        'add'          => "panel/api/inbounds/add",
        'update'       => "panel/api/inbounds/update/{id}",
        'delete'       => "panel/api/inbounds/del/{id}",
        'addClient'    => "panel/api/inbounds/addClient",
        'updateClient' => "panel/api/inbounds/updateClient/{id}"
    ];
    protected $sniffing = [
        'enabled' => true,
        'destOverride' => [
            "http",
            "tls"
        ]
    ];
    protected $settings = [
        'clients'    => [],
        'decryption' => "none",
        'fallbacks'  => []
    ];

    public function __construct($hostname) {
        $this->hostname = $hostname;
        $cookie_name    = substr(base64_encode($hostname), 1, 6);
        $this->cookie   = __DIR__.DIRECTORY_SEPARATOR."_{$cookie_name}_server.txt";
    }

    public function sizeConvert($size) {
        return $size * 1024 * 1024 * 1024;
    }

    public function generateId() {
        $data = random_bytes(16);
        assert(strlen($data) == 16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
        return vsprintf("%s%s-%s-%s-%s-%s%s%s", str_split(bin2hex($data), 4));
    }

    public function generateMail() {
        return substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz'), 1, 8);
    }

    public function generatePort() {
        $i = 0;
        while ($i < 1) {
            $port = rand(10000, 60000);
            if (!$this->getInboundByPort($port)) {
                $i++;
                return $port;
            }
        }
    }

    public function getTime($day) {
        return strtotime("+{$day} day") * 1000;
    }

    public function convertSize($byte, $precision = "2") {
        $units = array('بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت');
        $byte  = max($byte, 0);
        $pow   = floor(($byte ? log($byte) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $byte /= pow(1024, $pow);
        return round($byte, $precision)." ".$units[$pow];
    }

    public function convertDate($date) {
        return date("d F Y H:i:s", floor($date / 1000));
    }

    public function getDiffDays($date) {
        $now  = time();
        $date = floor($date / 1000);
        $diff = $date - $now;
        $days = round($diff / (60 * 60 * 24));
        return $days;
    }

    public function jsonEncode($data) {
        return json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getId() {
        return $this->id;
    }

    protected function getCookie() {
        return $this->cookie;
    }

    protected function getMethod($method) {
        if (isset($this->methods[$method])) {
            $method_path = $this->methods[$method];
            if (strpos($method_path, "{id}")) {
                return strtr($method_path, ['{id}' => $this->getId()]);
            }
            return $method_path;
        }
        return false;
    }

    protected function command($method, $data, $isPost = false) {
        $url = "{$this->hostname}/{$this->getMethod($method)}";
        $ch  = curl_init();

        $options = [
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:109.0) Gecko/20100101 Firefox/113.0",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_COOKIEFILE     => $this->getCookie(),
            CURLOPT_COOKIEJAR      => $this->getCookie(),
            CURLOPT_URL            => $url,
            CURLOPT_POST           => $isPost == true ? true : false,
            CURLOPT_CUSTOMREQUEST  => $isPost == true ? "POST" : "GET",
            CURLOPT_POSTFIELDS     => $data
        ];

        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);

        if (curl_error($ch)) {
            file_put_contents("xui_curl_error.txt", curl_error($ch));
        }

        curl_close($ch);
        return $result;
    }
}
?>