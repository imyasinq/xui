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
}
?>