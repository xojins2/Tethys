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

namespace Core\Model;

class CoreModel
{

    protected $c;
    protected $db;

    public function __construct(\Core\System\DiContainer $c)
    {
        $this->c = $c;

        //initialize some arrays
        $this->c->database->limit_columns = false;

        return true;
    }

    protected function dbConnect()
    {
        $this->db = new $this->c->server->db_type($this->c);
        return true;
    }

    /**
    * Filter the data returned from the datbase based on the defined
    * limit columns
    *
    */
    protected function limitResults()
    {
        if($this->c->database->limit_columns == false)
            return true;

        $new_arr = [];
        foreach($this->c->output->data as $key=>$value){
            foreach($value as $k=>$v){
                if(in_array($k,$this->c->database->limit_columns)){
                    $new_arr[$key][$k] = $v;
                }
            }
        }

        $this->c->output->data = $new_arr;

        return true;
    }

}
