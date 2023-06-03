<?php

namespace mRYasinQ\App;

trait Additions {
    public function generateVless($inbound_id, $header = "", $address = "") {
        $inbound = json_decode($this->inbound($inbound_id), true);
        if (isset($inbound) && $inbound['success'] == true) {
            $configs = [];
            $remark  = $inbound['obj']['remark'];
            $port    = $inbound['obj']['port'];
            $address = $address == "" ? $this->hostname : $address;
            $host    = $header == "" ? "" : "&host={$header}";
            $clients = json_decode($this->getClients($inbound_id), true);
            if ($clients) {
                $i = 0;
                foreach ($clients as $client) {
                    $uid                       = $client['id'];
                    $configs[$i]['inbound_id'] = $inbound_id;
                    $configs[$i]['uid']        = $uid;
                    $configs[$i]['config']     = "vless://{$uid}@{$address}:{$port}?encryption=none&security=none&type=tcp&headerType=http{$host}#{$remark}-{$inbound_id}";
                    $i++;
                }
                return $this->jsonEncode($configs);
            }
        }
        return false;
    }

    public function exportUIDVless($config) {
        preg_match("/(?<=:\/\/)(.*)(?=@)/", $config, $uid);
        if (isset($uid[0])) {
            return $uid[0];
        }
        return false;
    }
}
?>