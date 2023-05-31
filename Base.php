<?php
class Base {
    protected $protocol, $hostname, $port, $path;
    protected $cookie;
    protected $id;
    protected $methods = [
        'login'        => "login",
        'lists'        => "panel/api/inbounds/list",
        'list'         => "panel/api/inbounds/get/{id}",
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

    public function __construct($protocol, $hostname, $port, $path = "") {
        $this->protocol   = $protocol;
        $this->hostname   = $hostname;
        $this->port       = $port;
        $this->path       = $path;
        $this->cookie     = __DIR__.DIRECTORY_SEPARATOR."_{$hostname}_server.txt";
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
}
?>