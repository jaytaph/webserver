<?php

namespace WebServer\Protocol;

use WebServer\Comm\Connection;
use WebServer\Processor;
use WebServer\Protocol\Http\Kernel;
use WebServer\Protocol\Http\Request;
use WebServer\Protocol\Http\Response;
use WebServer\Protocol\Http\ResponseCode;

class Http implements Processor {

    /** @var Connection */
    protected $connection;

    protected $config;

    function __construct(array $config)
    {
        $this->config = $config;
    }

    function handle(Connection $connection)
    {
        $this->connection = $connection;

        $request = $this->readRequest();
        if ($request == null) return;


        $kernel = new Kernel($connection);
        $kernel->setConfiguration($this->config);
        $response = $kernel->process($request);

        if (! $response) {
            $response = new Response(ResponseCode::STATUS_INTERNAL_SERVER_ERROR);
            $this->returnResponse($response);
        }

        $this->returnResponse($response);

    }

    protected function readRequest()
    {
        // read initial header line
        $initial = $this->connection->readLine();

        $ret = preg_match('|^(.+)\s+(.+)\s+HTTP/(\d\.\d)$|', $initial, $matches);
        if (! $ret) {
            $response = new Response(ResponseCode::STATUS_BAD_REQUEST);
            $this->returnResponse($response);
            return null;
        }

        $method = $matches[1];
        $url = $matches[2];
        $version = $matches[3];
        $headers = array();

        if (! version_compare($version, "<= 1.1")) {
            $response = new Response(ResponseCode::STATUS_VERSION_NOT_SUPPORTED, null, array(), "");
            $this->returnResponse($response);
            return null;
        }


        // Read additional headers
        $s = "1";
        while (! empty($s)) {
            $s = $this->connection->readLine();
            if (empty($s)) continue;

            list($k, $v) = explode(":", $s, 2);
            $headers[strtolower($k)] = trim($v);
        };

        $body = "";

        $now = new \DateTime();
        $info = array(
            'request_time' => $now->format(\DateTime::RFC1123),
            'request_time_epoc' => $now->format('U'),
            'document_root' => $this->config['doc_root'],
            'remote_address' => $this->connection->getHost(),
            'remote_port' => $this->connection->getPort(),
        );

        $request = new Request($method, $url, $headers, $body, $info);

        print "> Request done\n";
        return $request;
    }


    /**
     * @param $conn
     * @param Response $response
     */
    protected function returnResponse(Response $response)
    {
        $this->setupResponseHeaders($response);
        $this->sendResponse($response);
    }

    protected function setupResponseHeaders(Response $response)
    {
        // Set default status message if no status message has been set
        if ($response->getStatusText() == null) {
            $response->setStatusText(ResponseCode::getStatusMessage($response->getStatusCode()));
        }

        // Add additional headers
        $headers = $response->getHeaders();
        $len = strlen($response->getBody());
        if ($len !== null) {
            $headers['content-length'] = strlen($response->getBody());
        }
        $headers['connection'] = 'close';
        $now = new \DateTime();
        $headers['date'] = $now->format(\DateTime::RFC1123);
        $headers['server'] = 'Saffire Webserver/1.0';
    }

    protected function sendResponse(Response $response)
    {
        print "> Response Status[".$response->getStatusCode()."]  Body[".strlen($response->getBody())."]\n";

        // Status line
        $this->connection->write("HTTP/1.1 ".$response->getStatusCode()." ".strtoupper($response->getStatusText()) . "\r\n");

        // Headers
        foreach ($response->getHeaders() as $key => $val) {
            $this->connection->write(ucwords(strtolower($key)) . ": " . ($val !== null ? $val : "") . "\r\n");
        }

        // Separator
        $this->connection->write("\r\n");

        // Body
        $this->connection->write($response->getBody());
    }
}
