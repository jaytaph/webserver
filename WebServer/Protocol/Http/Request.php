<?php

namespace WebServer\Protocol\Http;

class Request {

    /* @var string */
    protected $method;
    /* @var string */
    protected $uri;
    /* @var \ArrayObject */
    protected $headers;
    /* @var \ArrayObject */
    protected $info;

    /* @var string */
    protected $body;

    function __construct($method, $uri, array $headers = array(), $body = "", array $info = array())
    {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->headers = new \ArrayObject($headers);
        $this->body = $body;
        $this->info = new \ArrayObject($info);
    }

    function getMethod() {
        return $this->method;
    }

    function isGet() {
        return $this->method == "GET";
    }

    function isPost() {
        return $this->method == "POST";
    }

    function isSafe() {
        return in_array($this->method, array("GET", "OPTIONS", "HEAD"));
    }

    function isIdempotent() {
        return in_array($this->method, array("GET", "OPTIONS", "HEAD", "PUT", "DELETE"));
    }

    function getUri() {
        return $this->uri;
    }

    function getUriParts($part = null) {
        $uri = parse_url($this->uri);
        if ($part) {
            return isset($uri[$part]) ? $uri[$part] : false;
        }

        return $uri;
    }


    function getHeaders()
    {
        return $this->headers;
    }

    function hasHeader($header) {
        return isset($this->headers[$header]);
    }

    function getHeader($header, $default_value = false) {
        return isset($this->headers[$header]) ? $this->headers[$header] : $default_value;
    }


    function hasInfo($header) {
        return isset($this->info[$header]);
    }

    function getInfo($header) {
        return isset($this->info[$header]) ? $this->info[$header] : null;
    }

    function getScheme() {
        return $this->getUriParts('scheme');
    }

    function getHost() {
        return $this->getUriParts('host');
    }

    function getPort() {
        return $this->getUriParts('port');
    }

    function getUser() {
        return $this->getUriParts('user');
    }

    function getPass() {
        return $this->getUriParts('pass');
    }

    function getPath() {
        return $this->getUriParts('path');
    }

    function getQuery() {
        return $this->getUriParts('query');
    }

    function getFragment() {
        return $this->getUriParts('fragment');
    }

    function getBody()
    {
        return $this->body;
    }


}
