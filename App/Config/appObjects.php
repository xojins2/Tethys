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

/**
* The appObjects file is used to create the needed objects for the appliction settings
*
*/

//load the container class
require_once('../../Core/System/DiContainer.php');

//create a new app container to hold all the settings
$c = new \Core\System\DiContainer();

//create a user sub-container
$c->user = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});

//create a server sub-container
$c->server = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});

//create a settings sub-container
$c->settings = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});

//create a data sub-container
$c->elements = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});

//create a db sub-container
$c->database = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});

//create an output sub-container
$c->output = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});

//create an output sub-container
$c->debug = $c->asShared(function ($c)
{
        return new \Core\System\DiContainer();
});
