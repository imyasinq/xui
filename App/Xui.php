<?php

namespace mRYasinQ\App;

class Xui extends Base {
    use Options;
    use Additions;
    
    public function __construct($hostname) {
        parent::__construct($hostname);
    }

    public function login($username, $password) {
        return $this->command('login', [
            'username' => $username,
            'password' => $password
        ], true);
    }

    public function logout() {
        if (unlink($this->getCookie())) {
            return $this->jsonEncode([
                'success' => true,
                'msg'     => "Logout was successful."
            ]);
        } else {
            return $this->jsonEncode([
                'success' => false,
                'msg'     => "Please try again."
            ]);
        }
    }

    public function inbounds() {
        return $this->command('inbounds', [], false);
    }

    public function inbound($inbound_id) {
        $this->setId($inbound_id);
        return $this->command('inbound', [], false);
    }

    public function add($remark, $exp = 0, $total = 0, $protocol = "vless", $listen = "", $enable = true) {
        return $this->command('add', [
            'enable' => true,
            'remark' => $remark,
            'listen' => $listen,
            'port' => $this->generatePort(),
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

    public function update($inbound_id, $enable, $remark, $expiryTime, $total, $settings, $streamSettings, $port, $protocol, $sniffing, $listen) {
        return $this->command('update', [
            'enable'         => $enable,
            'remark'         => $remark,
            'listen'         => $listen,
            'port'           => $port,
            'protocol'       => $protocol,
            'expiryTime'     => $expiryTime,
            'total'          => $total,
            'settings'       => $settings,
            'streamSettings' => $streamSettings,
            'sniffing'       => $sniffing,
        ], true);
    }

    public function delete($inbound_id) {
        $this->setId($inbound_id);
        return $this->command('delete', [], true);
    }

    public function addClient($inbound_id, $exp = 0, $total = 0, $email = "", $limitIp = 0, $flow = "") {
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

    public function updateClient($inbound_id, $uid, $id, $expiryTime, $totalGB, $email, $limitIp, $flow) {
        $this->setId($uid);
        return $this->command('updateClient', [
            'id' => $inbound_id,
            'settings' => $this->jsonEncode([
                'clients' => [
                    [
                        'id'           => $id,
                        'flow'         => $flow,
                        'email'        => $email,
                        'totalGB'      => $totalGB,
                        'expiryTime'   => $expiryTime,
                        'limitIp'      => $limitIp,
                        'delayedStart' => false,
                        'tgId'         => "",
                        'subId'        => ""
                    ]
                ]
            ])
        ], true);
    }
}
?>