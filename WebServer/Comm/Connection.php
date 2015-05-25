<?php

namespace WebServer\Comm;

class Connection {

    protected $conn;

    protected $host;
    protected $port;

    function __construct($conn)
    {
        $this->conn = $conn;

        socket_getpeername($this->conn, $this->host, $this->port);
    }

    public function close()
    {
        socket_close($this->conn);

        $this->conn = null;
    }

    /**
     * @param $s
     */
    public function write($s)
    {
        print "I> $s";
        socket_write($this->conn, $s);
    }

    /**
     * @return string
     */
    public function readLine()
    {
        $s = "";
        do {
            $c = socket_read($this->conn, 1, PHP_BINARY_READ);
            $s .= $c;
        } while ($c != "\n");

        $s = rtrim($s);
        print "O> $s\n";

        return $s;
    }

    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

}
