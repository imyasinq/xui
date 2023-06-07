<?php

namespace mRYasinQ\App;

trait Options {
    public function addAccount($remark, $address, $exp = 0, $total = 0, $header = "") {
        $add = json_decode($this->add($remark, $exp, $total), true);
        if (isset($add) && $add['success'] == true) {
            $inbound_id = $add['obj']['id'];
            $addClient  = json_decode($this->addClient($inbound_id, $exp, $total), true);
            if (isset($addClient) && $addClient['success'] == true) {
                return $this->generateVless($inbound_id, $address, $header);
            }
        }
        return false;
    }

    public function getInboundByUID($uid) {
        $inbounds = json_decode($this->inbounds(), true);
        if (isset($inbounds) && $inbounds['success'] == true) {
            if (count($inbounds['obj']) != 0) {
                foreach ($inbounds['obj'] as $inbound) {
                    $clients = json_decode($this->getClients($inbound['id']), true);
                    if ($clients) {
                        foreach ($clients as $client) {
                            if ($client['id'] == $uid) {
                                return $this->jsonEncode($inbound);
                            }
                        }
                    }
                }
            }
        }
        return false;
    }

    public function getInboundByPort($port) {
        $inbounds = json_decode($this->inbounds(), true);
        if (isset($inbounds) && $inbounds['success'] == true) {
            if (count($inbounds['obj']) != 0) {
                foreach ($inbounds['obj'] as $inbound) {
                    if ($inbound['port'] == $port) {
                        return $this->jsonEncode($inbound);
                    }
                }
            }
        }
        return false;
    }

    public function getClients($inbound_id) {
        $inbound = json_decode($this->inbound($inbound_id), true);
        if (isset($inbound) && $inbound['success'] == true) {
            $settings = json_decode($inbound['obj']['settings'], true);
            $clients  = $settings['clients'];
            if (count($clients) != 0) {
                return $this->jsonEncode($clients);
            }
        }
        return false;
    }

    public function getClientIndex($inbound_id, $uid) {
        $clients = json_decode($this->getClients($inbound_id), true);
        if ($clients) {
            $i = 0;
            foreach ($clients as $client) {
                if ($client['id'] == $uid) {
                    return $i;
                }
                $i++;
            }
        }
        return false;
    }

    public function changeStatusInbound($inbound_id) {
        $inbound = json_decode($this->inbound($inbound_id), true);
        if (isset($inbound) && $inbound['success'] == true) {
            $status  = $inbound['obj']['enable'] == false ? true : false;
            $inbound = $inbound['obj'];
            return $this->update($inbound_id, $status, $inbound['remark'], $inbound['expiryTime'], $inbound['total'], $inbound['settings'], $inbound['streamSettings'], $inbound['port'], $inbound['protocol'], $inbound['sniffing'], $inbound['listen']);
        }
        return false;
    }

    public function changeClientUID($inbound_id, $uid) {
        $client_index = $this->getClientIndex($inbound_id, $uid);
        if ($client_index == false) {
            $clients = json_decode($this->getClients($inbound_id), true);
            $client  = $clients[$client_index];
            return $this->updateClient($inbound_id, $uid, $this->generateId(), $client['expiryTime'], $client['totalGB'], $client['email'], $client['limitIp'], $client['flow']);
        }
        return false;
    }

    public function deleteDisableInbound() {
        $inbounds = json_decode($this->inbounds(), true);
        if (isset($inbounds) && $inbounds['success'] == true) {
            if (count($inbounds['obj']) != 0) {
                $i = 0;
                foreach ($inbounds['obj'] as $inbound) {
                    if ($inbound['enable'] == false) {
                        $delete = json_decode($this->delete($inbound['id']), true);
                        if (isset($delete) && $delete['success'] == true) {
                            $i++;
                        } else {
                            return $this->jsonEncode([
                                'success' => false,
                                'msg'     => $i
                            ]);
                        }
                    }
                }
                return $this->jsonEncode([
                    'success' => true,
                    'msg'     => $i
                ]);
            }
        }
        return false;
    }
}
?>