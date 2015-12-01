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

use Hoa\Socket\Transport as SUT;
use Hoa\Test;

/**
 * Class \Hoa\Socket\Test\Unit\Transport.
 *
 * Test suite for the transport.
 *
 * @copyright  Copyright © 2007-2015 Hoa community
 * @license    New BSD License
 */
class Transport extends Test\Unit\Suite
{
    public function case_get()
    {
        $this
            ->when($result = SUT::get())
            ->then
                ->array($result)
                ->containsValues([
                    'tcp',
                    'udp',
                    'unix',
                    'udg'
                ]);
    }

    public function case_exists()
    {
        $this
            ->boolean(SUT::exists('tcp'))
                ->isTrue();
    }

    public function case_not_exists()
    {
        $this
            ->boolean(SUT::exists('unknown'))
                ->isFalse();
    }

    public function case_wrapper()
    {
        $this
            ->when($result = SUT::getWrapper('scheme'))
            ->variable($result)
                ->isNull()
            ->when($result = SUT::wrapperExists('scheme'))
            ->boolean($result)
                ->isFalse();

        SUT::registerWrapper('scheme', function ($scheme) {
            return $scheme;
        });

        $this
            ->when($result = SUT::getWrapper('scheme'))
            ->variable($result)
                ->isNotNull()
            ->object($result)
                ->isCallable()
            ->when($result = SUT::wrapperExists('scheme'))
            ->boolean($result)
                ->isTrue();
    }
}
