<?php

namespace Xui\Web;
use \Xui\App\Xui;

class Soft {
    public function __construct($server, $data) {
        $response = false;
        if (isset($server['REQUEST_METHOD']) && $server['REQUEST_METHOD'] == "GET" && isset($server['PATH_INFO']) && !empty($server['PATH_INFO'])) {
            $response = $this->sendRequest($server['PATH_INFO'], $data);
        } else {
            $response = $this->getMessage(false, "Not Found.");
        }
        echo $response;
        return $response;
    }

    private function sendRequest($path, $data) {
        if (isset($data['hostname']) && isset($data['username']) && isset($data['password'])) {
            $xui = new Xui($data['hostname']);
            $login = json_decode($xui->login($data['username'], $data['password']), true);
            if (isset($login) && $login['success'] == true) {
                $result = false;
                switch($path) {
                    case '/add':
                        if (isset($data['remark']) && isset($data['address']) && isset($data['exp']) && isset($data['total']) && isset($data['header'])) {
                            $result = $xui->addAccount($data['remark'], $data['address'], $data['exp'], $data['total'], $data['header']);
                        }
                        break;

                    case '/addInbound':
                        if (isset($data['remark']) && isset($data['exp']) && isset($data['total'])) {
                            $result = $xui->add($data['remark'], $data['exp'], $data['total']);
                        }
                        break;

                    case '/addClient':
                        if (isset($data['id']) && isset($data['exp']) && isset($data['total'])) {
                            $result = $xui->addClient($data['id'], $data['exp'], $data['total']);
                        }
                        break;

                    case '/inbounds':
                        $result = $xui->inbounds();
                        break;

                    case '/getStatusById':
                        if (isset($data['id'])) {
                            $result = $xui->getStatusById($data['id']);
                        }
                        break;

                    case '/getStatusByUID':
                        if (isset($data['uid'])) {
                            $result = $xui->getStatusByUID($data['uid']);
                        }
                        break;

                    case '/getStatusByPort':
                        if (isset($data['port'])) {
                            $result = $xui->getStatusByPort($data['port']);
                        }
                        break;

                    case '/changeStatusInbound':
                        if (isset($data['id'])) {
                            $result = $xui->changeStatusInbound($data['id']);
                        }
                        break;

                    case '/changeClientUID':
                        if (isset($data['id']) && isset($data['uid'])) {
                            $result = $xui->changeClientUID($data['id'], $data['uid']);
                        }
                        break;

                    case '/delete':
                        if (isset($data['id'])) {
                            $result = $xui->delete($data['id']);
                        }
                        break;

                    case '/deleteDisabled':
                        $result = $xui->deleteDisableInbound();
                        break;

                    default:
                        $result = $this->getMessage(false, "Not Found.");
                        break;
                }
                $xui->logout();
                return $result;
            } else {
                return json_encode($login);
            }
        }

        return $this->getMessage(false, "Please try again.");
    }

    private function getMessage(bool $status, string $message) {
        return json_encode([
            'success' => $status,
            'msg'     => $message
        ]);
    }
}
?>