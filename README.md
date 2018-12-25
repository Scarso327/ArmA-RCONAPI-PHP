# ArmA-RCONAPI-PHP

## Usage
Making remote requests to this API is quite simple and easy. Below you're able to see an example of how it can be done. The key is checked against an array in the 'settings.php' file to allow us to only allow those we want to make requests, make requests. Currently I've not added any kick or ban functions however as a more secure authentication (Passworded) system would be required as a key is not the most secure.

```php
$players = json_decode(file_get_contents("http://s1.tritanhub.co.uk/getPlayers/?key=5FD924625F6AB16A19CC9807C7C506AE1813490E4BA675F843D5A10E0BAACDB8"));
print_r($players);
```

If you only wish to use this API locally then you can set 'is-online' to false and all web-based requests will be ignored and you can still make requests using the code below as long as you're on the same server.

```php
include_once '_API/includes.php';
$command = new commands;
$command->getPlayers();
```

All settings for the server (IP Address, RCON Port and RCON Password) can be set in the 'settings.php' file.