<?php

namespace mRYasinQ\App;

class Xui extends Base {
    public function __construct($protocol, $hostname, $port, $path = "") {
        parent::__construct($protocol, $hostname, $port, $path);
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

    public function add($remark, $port = 0, $exp = 0, $total = 0, $protocol = "vless", $enable = true, $streamSettings = "tcp", $listen = "") {
        return $this->command('add', [
            'enable' => true,
            'remark' => $remark,
            'listen' => $listen,
            'port' => $port,
            'protocol' => $protocol,
            'expiryTime' => $exp == 0 ? $exp : $this->getTime($exp),
            'total' => $total == 0 ? $total : $this->sizeConvert($total),
            'settings' => $this->jsonEncode($this->settings),
            'streamSettings' => $this->jsonEncode([
                'network'     => "tcp",
                'security'    => "none",
                'tcpSettings' => [
                    'acceptProxyProtocol' => false,
                    'header'              => [
                        'type'     => "http",
                        'request'  => [
                            'method'  => "GET",
                            'path'    => ["/"],
                        ],
                        'response' => [
                            'version' => "1.1",
                            'version' => "200",
                            'reason'  => "OK",
                        ]
                    ]
                ]
            ]),
            'sniffing' => $this->jsonEncode($this->sniffing),
        ], true);
    }

    public function changeStatusInbound($inbound_id) {
        $list = json_decode($this->list($inbound_id), true);
        if (isset($list) && $list['success'] == true) {
            $inbound = $list['obj'];
            $status = $inbound['enable'] == true ? false : true;
            return $this->command('update', [
                'enable' => $status,
                'remark' => $inbound['remark'],
                'listen' => $inbound['listen'],
                'port' => $inbound['port'],
                'protocol' => $inbound['protocol'],
                'expiryTime' => $inbound['expiryTime'],
                'total' => $inbound['total'],
                'settings' => $inbound['settings'],
                'streamSettings' => $inbound['streamSettings'],
                'sniffing' => $inbound['sniffing'],
            ], true);
        }
        return false;
    }

    public function delete($inbound_id) {
        $this->setId($inbound_id);
        return $this->command('delete', [], true);
    }

    public function addClient($inbound_id, $email = "", $total = 0, $exp = 0, $limitIp = 0, $flow = "") {
        return $this->command('addClient', [
            'id' => $inbound_id,
            'settings' => $this->jsonEncode([
                'clients' => [
                    [
                        'id'           => $this->generateId(),
                        'flow'         => $flow,
                        'email'        => $email,
                        'totalGB'      => $total == 0 ? $total : $this->sizeConvert($total),
                        'expiryTime'   => $exp == 0 ? $exp : $this->getTime($exp),
                        'limitIp'      => $limitIp,
                        'delayedStart' => false,
                        'tgId'         => "",
                        'subId'        => ""
                    ]
                ]
            ])
        ], true);
    }

    public function changeUID($inbound_id) {
        $list = json_decode($this->list($inbound_id,), true);
        if (isset($list) && $list['success'] == true) {
            $settings = json_decode($list['obj']['settings'], true);
            $client   = $settings['clients'][0];
            if (isset($client)) {
                $this->setId($client['id']);
                return $this->command('updateClient', [
                    'id' => $inbound_id,
                    'settings' => $this->jsonEncode([
                        'clients' => [
                            [
                                'id'           => $this->generateId(),
                                'flow'         => $client['flow'],
                                'email'        => $client['email'],
                                'totalGB'      => $client['totalGB'],
                                'expiryTime'   => $client['expiryTime'],
                                'limitIp'      => $client['limitIp'],
                                'delayedStart' => false,
                                'tgId'         => "",
                                'subId'        => ""
                            ]
                        ]
                    ])
                ], true);
            }
        }
        return false;
    }
}
?>