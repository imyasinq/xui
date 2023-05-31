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
}
?>