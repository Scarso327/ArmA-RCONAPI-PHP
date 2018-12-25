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

        $players = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $players);
        preg_match_all("#(\d+)\s+([0-9a-fA-F]+)\s([perm|\d]+)\s([\S ]+)$#im", $players, $str);
        $players = core::formatList($str);

        print_r($players);
    }
}