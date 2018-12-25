<?php
return array(
    "server-ip" => "127.0.0.1",
    "server-port" => 2301,
    "rcon-pw" => "test",
    "keys" => array (
        "5FD924625F6AB16A19CC9807C7C506AE1813490E4BA675F843D5A10E0BAACDB8"
    ),
    "is-online" => true // For more info read the comments below
);

/* IS-ONLINE

If this is false you'll have to call for commands like this and won't be able to do it with URL based requests:
    include_once '_API/includes.php';
    $command = new commands;
    $command->{$this->command}();

If true you can request it using the above method and this one:
    $url = "http://api.example.com/getPlayers/?key=BLAH"; 
    $json_object= file_get_contents($url);
    $json_decoded = json_decode($json_object);
*/