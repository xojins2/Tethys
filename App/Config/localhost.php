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

//Server Settings
$c->server->db_user        = 'local_dev';   //oracle schema name
$c->server->db_pass        = 'password';     //db password
$c->server->db_name        = '//192.168.56.110:1521/orcl';   //db server to access
$c->server->http           = 'http://';  //use http or https
$c->server->app_path       = '/media/sf_Source/testcode/Tethys';   //the path on the filesystem where the controllers folder is
$c->server->ds             = '/';   //the directory seperator to use based on the operating system

//application settings
$c->settings->core_version   = '14.1.1.1';   //app version
$c->settings->module_label   = 'Tethys 14.1 Test';    //app name
$c->settings->dev_env        = true;   //dev environment sets profiling and debug error messages
$c->settings->max_file_size  = 5242880;  //5mb
$c->settings->admin_email    = '';   //default admin email
