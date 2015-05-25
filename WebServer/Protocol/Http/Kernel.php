<?php

namespace WebServer\Protocol\Http;

class Kernel
{

    protected $config;

    /**
     * @param array $config
     */
    public function setConfiguration(array $config)
    {
        $this->config = $config;
    }


    /**
     * @param Request $request
     * @return Response
     */
    public function process(Request $request)
    {
        print "PATH: ".$request->getPath()."\n";

        $target = realpath($this->config['doc_root'] . $request->getPath());
        if (strpos($target, $this->config['doc_root']) !== 0) {
            return new Response(ResponseCode::STATUS_NOT_FOUND);
        }


        $response = new Response(ResponseCode::STATUS_OK);
        $response->setBody(file_get_contents($target));
        $headers = $response->getHeaders();
        $headers['Content-Type'] = "text/html";

        //$response->setBody("<h1>And all is well!</h1><pre>" . print_r($request, true));

        return $response;
    }
}

