<?php

require __DIR__ . '/vendor/autoload.php';

use NINEJKH\PdnsPipe\Stream;
use NINEJKH\PdnsPipe\Backends\PipeBackend1;

$stream = new Stream;
$pipe_backend = new PipeBackend1($stream);

$stream->listen();

while (true) {
    if ($stream->recv($data)) {
        $pipe_backend->process($data);
    }
}
