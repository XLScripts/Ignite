<?php namespace Ignite\Components;

class IncomingRequest {
    public $input;

    public function __construct() {
        $this->input   = new InputReciever();
        $this->headers = getallheaders();
    }

    public function getIpAddress() {
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
            $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
        }
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
            $ip = $client;
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
            $ip = $forward;
        else 
            $ip = $remote;

        return $ip;
    }

    public function getServer() {
        return $_SERVER;
    }

    public function getUri() {
        return $_SERVER['REQUEST_URI'];
    }

    public function getMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function isAJAX() {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return true;
            
        return false;
    }

    public function getHeaders() {
        return $this->headers;
    }

    public function getHeaderLine($line) {
        print_r($this->headers);
        return $this->headers[$line];
    }
}