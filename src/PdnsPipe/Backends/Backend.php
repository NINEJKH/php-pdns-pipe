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

interface Backend
{
    public function process($data);

    public function getVersion();

    public function helo(int $version);

    public function ehlo();

    public function lookup($qname, $qclass, $qtype, $id, $remote_addr);

    public function fail();
}
