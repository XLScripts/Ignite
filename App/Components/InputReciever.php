<?php namespace Ignite\Components;

class InputReciever {
    public function data($var = null) {
        if(!$var) return $_REQUEST;
        else 
            return isset($_REQUEST[$var]) ? $_REQUEST[$var] : null;
    }

    public function get($var = null) {
        if(!$var) return $_GET;
        else 
            return isset($_GET[$var]) ? $_GET[$var] : null;
    }
    
    public function post($var = null) {
        if(!$var) return $_POST;
        else 
            return isset($_POST[$var]) ? $_POST[$var] : null;
    }

    public function json() {
        return json_decode(file_get_contents('php://input'), true);
    }
}