<?php

// breaks down the URL and actually sends the requests...
class Core {

    private $command = null;
    public $params = array();

    public function __construct() {
        self::URLSetup();

        $settings = require_once "settings.php";
        if ($settings["is-online"]) {
            if((isset($_GET['key'])) && in_array($_GET['key'], $settings["keys"])) {
                if (!$this->command) {
                    throw new \Exception("No command included...");
                } else {
                    $command = new Commands;
                    $command->{$this->command}();
                } 
            } else {
                die("Security Failuire...");
            }
        } else {
            die("FAILED: This api is configured for local requests only...");
        }
    }

    public function URLSetup() {
        if (isset($_GET['url'])) {
            $url = trim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            $this->command = isset($url[0]) ? $url[0] : null;
            unset($url[0]);
            $this->params = array_values($url);
        }
    }

    /**
     * Converts BE text "array" list to array
     *
     * @author nerdalertdk (https://github.com/nerdalertdk)
     * @link https://github.com/Nizarii/arma-rcon-class-php/issues/4 The related Github issue
     *
     * @param $str array
     *
     * @return array
     */
    public function formatList($str)
    {
        // Remove first array
        array_shift($str);
        // Create return array
        $result = array();
        // Loop true the main arrays, each holding a value
        foreach($str as $key => $value) {
            // Combines each main value into new array
            foreach($value as $keyLine => $line) {
                $result[$keyLine][$key] = trim($line);
            }
        }
        return $result;
    }
    /**
     * Remove control characters
     *
     * @author nerdalertdk (https://github.com/nerdalertdk)
     * @link https://github.com/Nizarii/arma-rcon-class-php/issues/4 The related GitHub issue
     *
     * @param $str string
     *
     * @return string
     */
    public function cleanList($str)
    {
        return preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $str);
    }
}