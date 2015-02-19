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

namespace App\Application\v1\Controllers;

class CommonController extends \Core\Controller\CoreController
{

    public function __construct($controller,$action,$container)
    {
        parent::__construct($controller,$action,$container);

        return true;
    }

    /**
    * Check to see if the input parameter data is set and that the correct
    * number of elements are present for the procedure
    *
    * @param array $data
    * @param int $element_count
    */
    protected function checkData($data,$required_element_count)
    {
        if ($data)
            $data = explode(",",$data);

        if(!is_array($data) || count($data) < $required_element_count){
            ThrowError('Wrong parameters');
        }

        $this->c->database->input_data = $data;

    }

    protected function checkRequestType($type=false)
    {
        if($this->c->server->request_type != $type) {
            $this->c->output->data = ['Bad Request'];
            $this->outputJson(405);
            ThrowError($this->c->output->data);
        }
    }

}
