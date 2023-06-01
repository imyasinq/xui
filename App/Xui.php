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

    public function inbounds() {
        return $this->command('inbounds', [], false);
    }

    public function inbound($id) {
        $this->setId($id);
        return $this->command('inbound', [], false);
    }
}
?>