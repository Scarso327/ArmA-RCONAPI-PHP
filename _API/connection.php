<?php
// Handles our socket connection to the actual server...
class Connection {

    private $serverIP;
    private $serverPort;
    private $serverPW;

    private $connection; // Holds our socket conneciton...
    private $isConnected = false;

    // Makes a new connection that we can then use for 
    public function __construct() {
        $settings = require "settings.php";
        $this->serverIP = $settings["server-ip"];
        $this->serverPort = $settings["server-port"];
        $this->serverPW = $settings["rcon-pw"];

        // Do checks please daddy...
        if (!is_string($this->serverIP) || !is_int($this->serverPort) || !is_string($this->serverPW)) {
            throw new \Exception('Settings provided are not the correct data types!');
        }
        if ($this->isConnected) {
            $this->closeSocket();
        }

        // Make the conneciton!
        $this->connection = @fsockopen("udp://$this->serverIP", $this->serverPort, $errno, $errstr, 1);
        if (!$this->connection) {
            // I could include the error and errstr here like on http://uk.php.net/manual/en/function.fsockopen.php
            // But I don't want to. You can always change it of course
            throw new \Exception('Failed to create socket!');
        }
        $this->isConnected = true;

        // After reading up on these, I decided these settings = good.
        stream_set_timeout($this->connection, 1);
        stream_set_blocking($this->connection, true);

        // Now we need to actually login to the RCON with our conneciton...
        $msgToBeSent = sprintf('%x', crc32(chr(255).chr(00).trim($this->serverPW)));
        $msgToBeSent = array(substr($msgToBeSent,-2,2),substr($msgToBeSent,-4,2),substr($msgToBeSent,-6,2),substr($msgToBeSent,0,2));
        $msgToBeSent = 'BE'.chr(hexdec($msgToBeSent[0])).chr(hexdec($msgToBeSent[1])).chr(hexdec($msgToBeSent[2])).chr(hexdec($msgToBeSent[3]));
        $msgToBeSent .= chr(hexdec('ff')).chr(hexdec('00')).$this->serverPW;

        if((fwrite($this->connection, $msgToBeSent)) === false) {
            throw new \Exception("Login request failed...");
        }
        $read = fread($this->connection, 16);
        if (@ord($read[strlen($read)-1]) == 0) {
            throw new \Exception('Login failed, wrong password or wrong port!');
        }
    }

    // Just close the socket if we destruct....
    public function __destruct()
    {
        $this->closeSocket();
    }

    public function closeSocket() {
        if (!$this->isConnected) {
            return;
        }
        fclose($this->connection);
        $this->connection = null;
        $this->isConnected = false;
    }

    public function makeRequest($command, $feedback = false) {
        if (!is_string($command)) {
            throw new \Exception('Wrong parameter type!');
        }

        $req = $this->request($command);
        if($feedback) {
            return $req;
        }
    }

    protected function request($command) {
        if(!$this->isConnected) {
            throw new \Exception("Request Made with No Socket Connection");
        }

        // This creates the CRC data. It's error checking for packets (Learnt this in college, I hate networking...)
        $msgToBeSent = sprintf('%x', crc32(chr(255).chr(01).chr(hexdec(sprintf('%01b', 0))).$command));
        $msgToBeSent = array(substr($msgToBeSent,-2,2),substr($msgToBeSent,-4,2),substr($msgToBeSent,-6,2),substr($msgToBeSent,0,2));
        $msgToBeSent = 'BE'.chr(hexdec($msgToBeSent[0])).chr(hexdec($msgToBeSent[1])).chr(hexdec($msgToBeSent[2])).chr(hexdec($msgToBeSent[3])).chr(hexdec('ff')).chr(hexdec('01')).chr(hexdec(sprintf('%01b', 0)));
        $msgToBeSent = $msgToBeSent.$command; // Finally we'll add the command to the end...

        // Ensure the writing and sending of our packet is okay...
        if(!(fwrite($this->connection, $msgToBeSent))) {
            throw new \Exception("Failed to write command...");
        }

        // Return me daddy (Credit to schaeferfelix on github as I couldn't get this working...)
        $get = function() {
            return substr(fread($this->connection, 102400), strlen($msgToBeSent));
        };
        
        $output = '';
        do {
            $answer = $get();
            while (strpos($answer, 'RCon admin') !== false) {
                $answer = $get();
            }
            $output .= $answer;
        } while (!empty($answer));
        return $output;
    }
}