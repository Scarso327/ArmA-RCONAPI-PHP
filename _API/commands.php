<?php

class commands {

    /**
     * 
     * Is Public: Yes (Can be called without providing authentication)
     * 
     */
    public static function getPlayers() {
        $connection = new connection;
        $players = $connection->makeRequest("players", true);
        $connection->closeSocket(); // NOT NEEDED ANYMORE SO KILL IT OFF PLEASE K THANKS
        
        $players = core::cleanList($players);
        preg_match_all("#(\d+)\s+(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d+\b)\s+(\d+)\s+([0-9a-fA-F]+)\(\w+\)\s([\S ]+)$#im", $players, $str);
        $players = core::formatList($str);
        header('Content-Type: application/json');
        echo json_encode($players);
    }
}