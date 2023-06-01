<?php

namespace mRYasinQ\App;

class Xui extends Base {
    public function __construct($protocol, $hostname, $port, $path = "") {
        parent::__construct($protocol, $hostname, $port, $path = "");
    }

    public function login($username, $password) {
        return $this->command('login', [
            'username' => $username,
            'password' => $password
        ], true);
    }
}
?>