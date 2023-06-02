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
                foreach ($clients as $client) {
                    $uid       = $client['id'];
                    $configs[] = "vless://{$uid}@{$address}:{$port}?encryption=none&security=none&type=tcp&headerType=http{$host}#{$remark}-{$inbound_id}";
                }
                return $this->jsonEncode($configs);
            }
        }
        return false;
    }
}
?>