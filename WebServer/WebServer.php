<?php

namespace WebServer;

use WebServer\Comm\Socket;
use WebServer\Protocol\Http;

class WebServer {

    /* @var string */
    protected $host;

    /* @var int */
    protected $port;

    /* @var string */
    protected $doc_root;

    /* @var string */
    protected $index;

    /** @var Http */
    protected $processor;

    /**
     * @param string $host
     * @param int $port
     * @param string $index
     */
    function __construct($host = "127.0.0.1", $port = 4000, $doc_root = "docroot", $index_file = "index.html")
    {
        // Use current working dir as the start point for relative document roots
        if ($doc_root[0] != '/') {
            $doc_root = getcwd() . '/' . $doc_root;
        }

        // Configuration options that are passed to others
        $this->config = array(
            'host' => $host,
            'port' => $port,
            'doc_root' => realpath($doc_root),
            'index_file' => $index_file,
        );

        $this->processor = new Http($this->config);
    }

    /**
     *
     */
    function serve()
    {
        $socket = new Socket($this->config['host'], $this->config['port']);

        print "> Serving Saffire on ".$this->config['host'].":".$this->config['port']."\n";
        print "> Press CTRL-C to quit.\n";

        while (true) {
            $connection = $socket->getIncomingConnection();

            $this->processor->handle($connection);

            $connection->close();
        }
    }

}
