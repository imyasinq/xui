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
}
?>