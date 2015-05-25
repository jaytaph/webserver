<?php

namespace WebServer\Comm;

class Socket {

    protected $socket;

    function __construct($host, $port)
    {
        if ( ($this->socket = socket_create(AF_INET, SOCK_STREAM, 0)) < 0) {
            throw new \RuntimeException('Cannot create socket: '.socket_strerror($this->socket)."\n");
        }

        if ( (socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1)) !== true) {
            throw new \RuntimeException('Cannot set socket options: '.socket_strerror($this->socket)."\n");
        }

        if ( ($ret = socket_bind($this->socket, $host, $port)) !== true) {
            throw new \RuntimeException('Cannot bind socket: '.socket_strerror($this->socket)."\n");
        }

        if ( ($ret = socket_listen($this->socket, 0)) !== true) {
            throw new \RuntimeException('Cannot listen on socket: '.socket_strerror($this->socket)."\n");
        }

        if ( ($ret = socket_set_block($this->socket)) !== true) {
            throw new \RuntimeException('Cannot set non-blocking on socket: '.socket_strerror($this->socket)."\n");
        }
    }

    /**
     * @return Connection
     */
    function getIncomingConnection() {
        $socket = null;

        while (true) {
            $socket = @socket_accept($this->socket);
            if ($socket === false) {
                usleep(1000);
                continue;
            }

            if (! is_resource($socket)) {
                die("Error while accepting socket: " . socket_strerror($socket));
            }

            if ($socket)  {
                break;
            }
        }

        return new Connection($socket);
    }

}
