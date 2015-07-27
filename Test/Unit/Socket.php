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

use Hoa\Socket\Socket as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Socket\Test\Unit\Socket.
 *
 * Test suite for the connection.
 *
 * @copyright  Copyright © 2007-2015 Hoa community
 * @license    New BSD License
 */
class Socket extends Test\Unit\Suite
{
    public function case_construct_exception()
    {
        $this
            ->exception(function () { new SUT('ws://host:443'); })
            ->exception(function () { new SUT('invalid-url-given'); });
    }

    public function case_construct_ipv4()
    {
        $this
            ->when($socket = new SUT('tcp://*:443'))
                ->string($socket->getPort())
                    ->isEqualTo('443')
                ->boolean($socket->hasPort())
                    ->isTrue()
                ->string($socket->getTransport())
                    ->isEqualTo('tcp')
                ->boolean($socket->hasTransport())
                    ->isTrue()
                ->string($socket->getAddress())
                    ->isEqualTo('0.0.0.0')
                ->integer($socket->getAddressType())
                    ->isEqualTo(SUT::ADDRESS_IPV4)
                ->string($socket->__toString())
                    ->isEqualTo('tcp://0.0.0.0:443');
    }

    public function case_construct_domain()
    {
        $this
            ->when($socket = new SUT('udp://domain.com'))
                ->integer($socket->getPort())
                    ->isEqualTo(-1)
                ->boolean($socket->hasPort())
                    ->isFalse()
                ->string($socket->getTransport())
                    ->isEqualTo('udp')
                ->boolean($socket->hasTransport())
                    ->isTrue()
                ->string($socket->getAddress())
                    ->isEqualTo('domain.com')
                ->integer($socket->getAddressType())
                    ->isEqualTo(SUT::ADDRESS_DOMAIN)
                ->string($socket->__toString())
                    ->isEqualTo('udp://domain.com');
    }

    public function case_construct_ipv6()
    {
        $url = 'tcp://2001:0db8:0000:85a3:0000:0000:ac1f:8001';
        $this
            ->when($socket = new SUT($url))
                ->string($socket->getAddress())
                    ->isEqualTo('2001:0db8:0000:85a3:0000:0000:ac1f:8001')
                ->integer($socket->getAddressType())
                    ->isEqualTo(SUT::ADDRESS_IPV6)
                ->string($socket->__toString())
                    ->isEqualTo($url);

        $url = 'tcp://[2001:0db8:0000:85a3:0000:0000:ac1f:8001]:443';
        $this
            ->when($socket = new SUT($url))
                ->string($socket->getPort())
                    ->isEqualTo('443')
                ->string($socket->getAddress())
                    ->isEqualTo('2001:0db8:0000:85a3:0000:0000:ac1f:8001')
                ->integer($socket->getAddressType())
                    ->isEqualTo(SUT::ADDRESS_IPV6)
                ->string($socket->__toString())
                    ->isEqualTo($url);
    }
}
