<?php

/**
 * This file is part of the NINEJKH/pdns-pipe library.
 *
 * (c) 9JKH (Pty) Ltd. <dev@9jkh.co.za>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace NINEJKH\PdnsPipe\Backends;

use NINEJKH\PdnsPipe\Stream;

abstract class Backends implements Backend
{
    protected $version;

    protected $stream;

    public function __construct(Stream $stream)
    {
        $this->stream = $stream;
    }

    public function process($data)
    {
        if (!is_array($data)) {
            $this->fail();
            return false;
        }

        switch ($data[0]) {
            case 'HELO':
                if (!$this->helo((int) $data[1])) {
                    $this->fail();
                } else {
                    $this->ehlo();
                }
                break;

            case 'Q':
                list ($qname, $qclass, $qtype, $id, $remote_addr) = array_slice($data, 1);
                $records = $this->lookup($zones, $qname, $qtype, $id, $remote_addr);
                if (!empty($records)) {
                    foreach ($records as $record) {
                        array_unshift($record, 'DATA');
                        $this->stream->send($record);
                    }
                }
                $this->stream->send('END');
                break;

            default:
                $this->fail();
                break;
        }

        return true;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function helo(int $version)
    {
        return ($version === $this->getVersion());
    }

    public function ehlo()
    {
        $this->stream->send([
            'OK',
            sprintf('NINEJKH/pdns-pipe (protocol version %d)', $this->getVersion()),
        ]);
    }

    public function fail()
    {
        $this->stream->send('FAIL');
    }
}
