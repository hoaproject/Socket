<?php

/**
 * Hoa
 *
 *
 * @license
 *
 * New BSD License
 *
 * Copyright © 2007-2015, Hoa community. All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the Hoa nor the names of its contributors may be
 *       used to endorse or promote products derived from this software without
 *       specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDERS AND CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace Hoa\Socket\Test\Unit;

use Hoa\Socket as LUT;
use Hoa\Socket\Transport as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Socket\Test\Unit\Client.
 *
 * Test suite for the client connection.
 *
 * @copyright  Copyright © 2007-2015 Hoa community
 * @license    New BSD License
 */
class Client extends Test\Unit\Suite
{
    public function case_set_socket_invalid_scheme_exception()
    {
        $this
            ->exception(function () {
                $client = new LUT\Client('scheme://domain.com');
            })
                ->isInstanceOf('\Hoa\Socket\Exception');
    }

    public function case_set_socket_invalid_url_exception()
    {
        $this
            ->exception(function () {
                $client = new LUT\Client('invalid-url');
            })
                ->isInstanceOf('\Hoa\Socket\Exception');
    }

    public function case_set_socket()
    {
        $this
            ->when($client = new LUT\Client('tcp://*:443'))
                ->object($client->getSocket())
                    ->isInstanceOf('\Hoa\Socket\Socket');
    }

    public function case_set_wrapped_socket()
    {
        $this
            ->given(
                SUT::registerWrapper('scheme', function ($scheme) {
                    $scheme = str_replace('scheme://', 'udp://', $scheme);

                    return new LUT\Socket($scheme);
                })
            )
            ->when($result = new LUT\Client('scheme://domain.com'))
                ->object($result->getSocket())
                    ->isInstanceOf('Hoa\Socket\Socket')
                ->string($result->getSocket()->getTransport())
                    ->isEqualTo('udp');
    }
}
