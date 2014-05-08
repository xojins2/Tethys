<?php
/**
* Tethys - a PHP 5.4 DI framework
*
* @author David Amatulli <xojins at gmail dot com>
* @copyright 2014 David Amatulli
* @link https://github.com/xojins
* @version 1.0
*
* MIT LICENSE
*
* Permission is hereby granted, free of charge, to any person obtaining
* a copy of this software and associated documentation files (the
* "Software"), to deal in the Software without restriction, including
* without limitation the rights to use, copy, modify, merge, publish,
* distribute, sublicense, and/or sell copies of the Software, and to
* permit persons to whom the Software is furnished to do so, subject to
* the following conditions:
*
* The above copyright notice and this permission notice shall be
* included in all copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
* EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
* MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
* NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
* LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
* OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
* WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

namespace Core\System;

/**
* Dependancy Injection Container
* Used to create an object with properties that can be created at run time
* and used to inject into the tethis framework
*/
class DiContainer
{
    protected $property = array();

    function __set($key, $value)
    {
        $this->property[$key] = $value;
    }

    function __get($key)
    {
        if (!isset($this->property[$key])) {
            throw new \InvalidArgumentException(sprintf('Value "%s" is not defined.', $key));
        }
        return is_callable($this->property[$key]) ? $this->property[$key]($this) : $this->property[$key];
    }

    function asShared($callable)
    {
        return function ($c) use ($callable)
        {
            static $object;

            if (is_null($object)) {
                $object = $callable($c);
            }

            return $object;
        };
    }
}
