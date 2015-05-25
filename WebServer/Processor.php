<?php

namespace WebServer;

use WebServer\Comm\Connection;

interface Processor {

    function handle(Connection $connection);

}
