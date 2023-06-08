<?php

namespace Xui\App;

trait Additions {
    public function generateVless($inbound_id, $address, $header = "") {
        $inbound = json_decode($this->inbound($inbound_id), true);
        if (isset($inbound) && $inbound['success'] == true) {
            $remark  = $inbound['obj']['remark'];
            $port    = $inbound['obj']['port'];
            $host    = $header == "" ? "" : "&host={$header}";
            $clients = json_decode($this->getClients($inbound_id), true);
            if ($clients) {
                $configs = [];
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

    public function inboundStatus(array $inbound) {
        $usage = $inbound['down'] + $inbound['up'];
        $total = $inbound['total'];
        $exp   = $inbound['expiryTime'];
        return $this->jsonEncode([
            'enable'     => $inbound['enable'],
            'inbound_id' => $inbound['id'],
            'remark'     => $inbound['remark'],
            'port'       => $inbound['port'],
            'protocol'   => $inbound['protocol'],
            'upload'     => $this->convertSize($inbound['up']),
            'download'   => $this->convertSize($inbound['down']),
            'usage'      => $this->convertSize($usage),
            'rem_trf'    => $total != 0 ? $this->convertSize($total - $usage) : 'نامحدود',
            'total'      => $total != 0 ? $this->convertSize($total) : "نامحدود",
            'rem_day'    => $exp != 0 ? $this->getDiffDays($exp) : "نامحدود",
            'exp'        => $exp != 0 ? $this->convertDate($exp) : "نامحدود"
        ]);
    }

    public function inboundsStatus() {
        return $this->jsonEncode([
            'inbounds'         => $this->countInbounds(),
            'enable_inbounds'  => $this->countInboundsByStatus(true),
            'disable_inbounds' => $this->countInboundsByStatus(false)
        ]);
    }
}
?>