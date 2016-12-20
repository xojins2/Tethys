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

/** Application Configuration Variables **/

//general application settings
$c->server->url        = '';   //http request var to access the uri
$c->server->logout_url = '/Index/logout';   //the url using the servername for logging out
$c->server->no_auth_url    = '/Index/unauthorized';   //the controller action that defines a failed auth attempt
$c->server->login_url  = '/Index';   //the url for loggin into the system
$c->server->ds             = '\\';   //the directory seperator to use based on the operating system

//database
$c->database->persistant = false;

//DO NOT EDIT BELOW THIS LINE

//set the server name
$c->server->name = str_replace('www.', '', $_SERVER['SERVER_NAME']);

//set the file to load
$server_file = '..'.$c->server->ds.'Config'.$c->server->ds . $c->server->name . '.php';

//load the config file for that server
if(file_exists($server_file)) {
    require_once($server_file);
} else {
    die($c->server->name.' Is Not Accessable.');
}
