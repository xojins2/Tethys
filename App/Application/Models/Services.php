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

namespace App\Application\Models;

class Services extends \Core\Model\CoreModel
{
    public function getColors()
    {
        $this->dbConnect();

        $this->c->output->colors = $this->db->select('colors');
        return true;
    }

    public function getCities()
    {
        $this->dbConnect();

        $this->c->output->cities = $this->db->select('cities');
        return true;
    }

    public function getVotes()
    {
        $this->dbConnect();

        if(!isset($this->c->database->color_id) && !$this->c->database->color_id){
            $votes = $this->db->select('votes');
        } else {
            $votes = $this->db->select('votes','*',array('color_id'=>$this->c->database->color_id),array('int'));
        }
        $total = 0;

        if($this->c->database->records > 0){
            if($this->c->database->records == 1){
                $total = $votes['votes'];
            } else {
                foreach($votes as $vote){
                    $total += $vote['votes'];
                }
            }
        }
        $this->c->output->votes = array('color_id'=>$this->c->database->color_id,'votes'=>$total);
        return true;
    }
}

