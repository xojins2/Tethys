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


/** Autoload any classes that are required **/
function __autoload($class) {
    global $c;

    $controller = ltrim($class, '\\');
    if ($lastNsPos = strripos($controller, '\\')) {
        $controller = substr($controller, $lastNsPos + 1);
    }
    if(class_exists($controller,false)) {
        return true;
    }
    $class = ltrim($class, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($class, '\\')) {
        $namespace = substr($class, 0, $lastNsPos);
        $class = substr($class, $lastNsPos + 1);
        $fileName  = str_replace('\\', $c->server->ds, $namespace) . $c->server->ds;
    }
    if (strripos($fileName, $c->server->ds.'Core'.$c->server->ds)!==FALSE) {
        $fileName = $c->server->app_path . $c->server->ds . $fileName;
    }
    $fileName .= str_replace('_', $c->server->ds, $class) . '.php';
    $fileName = $c->server->app_path.$c->server->ds.$fileName;
    if(file_exists($fileName)){
        require $fileName;
        return true;
    } else {
        return false;
    }
}
