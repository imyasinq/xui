<?php

namespace Xui\App;

trait Additions {
    public function generateVless($inbound_id, $address, $header = "") {
        $inbound = json_decode($this->inbound($inbound_id), true);
        if (isset($inbound) && $inbound['success'] == true) {
            $configs = [];
            $remark  = $inbound['obj']['remark'];
            $port    = $inbound['obj']['port'];
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
            return $this->jsonEncode([
                'uid' => $uid[0]
            ]);
        }
        return false;
    }

    public function convertSize($byte, $precision = "2") {
        $units = array('بایت', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت');
        $byte  = max($byte, 0);
        $pow   = floor(($byte ? log($byte) : 0) / log(1024));
        $pow   = min($pow, count($units) - 1);
        $byte /= pow(1024, $pow);
        return round($byte, $precision)." ".$units[$pow];
    }

    public function convertDate($date) {
        return date("d F Y H:i:s", floor($date / 1000));
    }

    public function getDiffDays($date) {
        $now  = time();
        $date = floor($date / 1000);
        $diff = $date - $now;
        $days = round($diff / (60 * 60 * 24));
        return $days;
    }
}
?>