<?php

/**
 * This file is part of the NINEJKH/pdns-pipe library.
 *
 * (c) 9JKH (Pty) Ltd. <dev@9jkh.co.za>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace NINEJKH\PdnsPipe;

use Exception;

class Stream
{
    protected $stdin;

    public function __destruct()
    {
        if (is_resource($this->stdin)) {
            fclose($this->stdin);
        }
    }

    public function listen()
    {
        $this->stdin = fopen('php://stdin', 'r');
    }

    public function recv(&$data)
    {
        $this->ping();

        $data = null;
        $read = [$this->stdin];
        $write = $except = [];

        $changed = stream_select($read, $write, $except, 2);

        if ($changed === false) {
            throw new Exception('stream_select failed');
        }

        if ($changed === 0) {
            return false;
        }

        $buf = rtrim(fgets($this->stdin));
        if (strlen($buf) === 0) {
            return false;
        }

        $data = explode("\t", $buf);
        return true;
    }

    public function send($data)
    {
        $this->ping();

        if (is_array($data)) {
            $data = implode("\t", $data);
        }

        echo $data . "\n";
    }

    protected function ping()
    {
        if (!is_resource($this->stdin)) {
            throw new Exception('lost stdin');
        }
    }
}
