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

//DebugBreak();
session_start();

date_default_timezone_set('America/New_York');

//set xhprof output - set to true to allow xhprof to profile site, false to turn off
define('USE_XHPROF',false);

//xhprof profiling section
(USE_XHPROF && extension_loaded('xhprof'))?include_once dirname(__FILE__).'/../../Core/Utils/xhprof/header.php':'';

//include the application settings file
require_once('../Config/appObjects.php');
require_once('../Config/appConfig.php');

//include the bootstrap file
require_once($c->server->app_path.$c->server->ds.'App'.$c->server->ds.'Shared'.$c->server->ds.'bootstrap.php');

//load the elements that are used by the application
//require_once '../Application/Elements/Elements.php';

//initialize the application
new \Core\System\Init($c);


//end the profiling session
(USE_XHPROF && extension_loaded('xhprof') && !$c->output->json)?include_once dirname(__FILE__).'/../../Core/Utils/xhprof/footer.php':'';

?>

