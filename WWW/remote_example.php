<?php
// You can run this code and get the list of players from the server on any other server...
$players = json_decode(file_get_contents("http://s1.tritanhub.co.uk/getPlayers/?key=5FD924625F6AB16A19CC9807C7C506AE1813490E4BA675F843D5A10E0BAACDB8"));
print_r($players);