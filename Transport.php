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

namespace Hoa\Socket;

/**
 * Class \Hoa\Socket\Transport.
 *
 * Basic transports manipulation.
 *
 * @copyright  Copyright © 2007-2015 Hoa community
 * @license    New BSD License
 */
class Transport
{
    /**
     * A collection of scheme wrappers
     * They are used to transformed an URI regarding to its application scheme.
     *
     * @var array
     */
    protected static $_wrappers = [];

    /**
     * Get all enable transports.
     *
     * @return  array
     */
    public static function get()
    {
        static $_ = null;

        if (null === $_) {
            $_ = stream_get_transports();
        }

        return $_;
    }

    /**
     * Check if a transport exists.
     *
     * @param   string  $transport    Transport to check.
     * @return  bool
     */
    public static function exists($transport)
    {
        return in_array(strtolower($transport), self::get());
    }

    /**
     * Register a new wrapper in the collection
     *
     * @param  string   $protocol The registered protocol (scheme://).
     * @param  callable $factory  The factory callable
     * @return void
     */
    public static function registerWrapper($protocol, callable $factory)
    {
        static::$_wrappers[$protocol] = $factory;

        return;
    }

    /**
     * Check if a wrapper exists in the collection
     *
     * @param  string $protocol The protocol to check
     * @return bool
     */
    public static function wrapperExists($protocol)
    {
        return true === array_key_exists($protocol, static::$_wrappers);
    }

    /**
     * Retrieve the wrapper registered for a protocol
     * @param  string $protocol The protocol to search
     * @return callable|null    The factory callable if found, null if not
     */
    public static function getWrapper($protocol)
    {
        if (false === static::wrapperExists($protocol)) {
            return null;
        }

        return static::$_wrappers[$protocol];
    }
}
