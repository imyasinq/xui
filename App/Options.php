<?php

namespace mRYasinQ\App;

trait Options {
    public function getClients($inbound_id) {
        $inbound = json_decode($this->inbound($inbound_id), true);
        if (isset($inbound) && $inbound['success'] == true) {
            $settings = json_decode($inbound['obj']['settings'], true);
            $clients  = $settings['clients'];
            if (count($clients) != 0) {
                return $clients;
            }
        }
        return false;
    }

    public function getClientIndex($inbound_id, $uid) {
        $clients = $this->getClients($inbound_id);
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
            $status = $inbound['obj']['enable'] == false ? true : false;
            return $this->update($inbound_id, $status, $inbound['expiryTime'], $inbound['total'], $inbound['settings'], $inbound['streamSettings'], $inbound['port'], $inbound['protocol'], $inbound['sniffing'], $inbound['listen']);
        }
        return false;
    }

    public function changeClientUID($inbound_id, $uid) {
        $client_index = $this->getClientIndex($inbound_id, $uid);
        if ($client_index) {
            $clients = $this->getClients($inbound_id);
            $client  = $clients[$client_index];
            return $this->updateClient($inbound_id, $uid, $this->generateId(), $client['totalGB'], $client['expiryTime'], $client['limitIp'], $client['flow']);
        }
        return false;
    }
}
?>