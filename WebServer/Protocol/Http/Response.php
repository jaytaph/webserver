<?php

namespace WebServer\Protocol\Http;

class Response {

    protected $status_code;
    protected $status_text;
    protected $body;
    protected $headers;

    function __construct($status_code, $status_text = null, array $headers = array(), $body = "")
    {
        $this->status_code = $status_code;
        $this->status_text = $status_text;
        $this->headers = new \ArrayObject($headers);
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return \ArrayObject
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param mixed $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @return null
     */
    public function getStatusText()
    {
        return $this->status_text;
    }

    /**
     * @param null $status_text
     */
    public function setStatusText($status_text)
    {
        $this->status_text = $status_text;
    }

}
