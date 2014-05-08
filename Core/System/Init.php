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

class Init
{
    use \Core\Common\Http;

    public function __construct(\Core\System\DiContainer $c)
    {
        //explode the url and get the controller, model, action
        $url_array = $this->explodeURL();

        //redirect to the login page if there is an auth issue
        if($url_array['action'] == $c->server->logout_url || $url_array['action'] == $c->server->no_auth_url) {
            session_destroy();
            $this->redirectUser($c->server->login_url);
        }

        //dynamically create the controller object
        $controller_name   = $url_array['controller'];
        $controller        = '\\App\\Application\\Controllers\\'.ucwords($controller_name);
        $dispatch          = new $controller($controller_name,$url_array['action'],$c);

        //if the method being called exists, then call it
        if ((int)method_exists($controller, $url_array['action']) || (int)method_exists($controller,'__call')) {
            call_user_func_array(array($dispatch,$url_array['action']),$url_array['queryString']);
        } else {
            //the user is requesting a method that does not exist so redirect to the login page
            session_destroy();
            $this->redirectUser($c->server->login_url);
            exit;
        }
    }
}
