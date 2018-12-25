<?php

class commands {

    /**
     * 
     * Is Public: Yes (Can be called without providing authentication)
     * 
     */
    public static function getPlayers($return = false) {
        $connection = new connection;
        $players = $connection->makeRequest("players", true);
        $connection->closeSocket(); // NOT NEEDED ANYMORE SO KILL IT OFF PLEASE K THANKS
        
        $players = core::cleanList($players);
        preg_match_all("#(\d+)\s+(\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}:\d+\b)\s+(\d+)\s+([0-9a-fA-F]+)\(\w+\)\s([\S ]+)$#im", $players, $str);
        $players = core::formatList($str);
        if($return) {
            return json_encode($players);
        } else {
            header('Content-Type: application/json');
            echo json_encode($players);
        }
    }

    // When making a request through URL you're unable to change or pass parameters to these files and so this command
    // only works if you can change the isLocal variable
    public static function sendMessage($isLocal = false, $target, $msg) {
        if($isLocal) {
            if(!is_int($target)) {
                die("Error: The player must be an intger!");
            }

            if(!is_string($msg)) {
                die("Error: The reason must be a string!");
            }

            if($msg != "") {
                $connection = new connection;
                $connection->makeRequest("say ".$target.$msg);
                $connection->closeSocket();
            } else {
                die("Error: No Message Input...");
            }
        } else {
            die("PROTECTED COMMAND"); // Would throw an Exception here but I want people to be able to get "feedback" for checking
        }
    } 

    // When making a request through URL you're unable to change or pass parameters to these files and so this command
    // only works if you can change the isLocal variable
    public static function kickPlayer($isLocal = false, $player, $reason) {
        if($isLocal) {
            if(!is_int($player)) {
                die("Error: The player must be an intger!");
            }

            if(!is_string($reason)) {
                die("Error: The reason must be a string!");
            }

            if($reason == "") {
                $reason = "No Reason Given";
            }

            $connection = new connection;
            $connection->makeRequest("kick $player $reason");
            $connection->closeSocket();
        } else {
            die("PROTECTED COMMAND"); // Would throw an Exception here but I want people to be able to get "feedback" for checking
        }
    }

    // When making a request through URL you're unable to change or pass parameters to these files and so this command
    // only works if you can change the isLocal variable
    public static function banPlayer($isLocal = false, $player, $time = 0, $reason = "Banned") {
        if($isLocal) {
            if(!is_int($player)) {
                die("Error: The player must be an intger!");
            }
            
            if(!is_string($reason) || !is_int($time)) {
                die("Error: The reason must be a string and the time must be an intger!");
            }

            if($reason == "") {
                $reason = "No Reason Given";
            }

            $connection = new connection;
            $connection->makeRequest("ban $player $time $reason");
            $connection->closeSocket();
            self::saveBans(true);
        } else {
            die("PROTECTED COMMAND"); // Would throw an Exception here but I want people to be able to get "feedback" for checking
        }
    }

    public static function saveBans($isLocal = false) {
        if($isLocal) {
            $connection = new connection;
            $connection->makeRequest("writeBans");
            $connection->closeSocket();
        } else {
            die("PROTECTED COMMAND"); // Would throw an Exception here but I want people to be able to get "feedback" for checking
        }
    }
}