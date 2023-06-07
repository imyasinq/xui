<?php

namespace Xui\Web;
use \Xui\App\Xui;

class Soft {
    public function __construct($server, $data) {
        if (isset($server['REQUEST_METHOD']) && $server['REQUEST_METHOD'] == "GET" && isset($server['PATH_INFO']) && !empty($server['PATH_INFO'])) {
            return $this->sendRequest($server['PATH_INFO'], $data);
        } else {
            return $this->getMessage(false, "Not Found.");
        }
    }

    private function sendRequest($path, $data) {
        if (isset($data['hostname']) && isset($data['username']) && isset($data['password'])) {
            $xui = new Xui($data['hostname']);
            $login = json_decode($xui->login($data['username'], $data['password']), true);
            if (isset($login) && $login['success'] == true) {
                $result = false;
                switch($path) {
                    default:
                        return $this->getMessage(false, "Not Found.");
                        break;
                }
                $xui->logout();
                echo $result;
                return $result;
            } else {
                return json_encode($login);
            }
        }
    }

    private function getMessage(bool $status, string $message) {
        return json_encode([
            'success' => $status,
            'msg'     => $message
        ]);
    }
}
?>