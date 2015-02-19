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

namespace Core\Database;

class StubDB
{
    protected $c;
    protected $package_name;
    protected $object_name;
    protected $input_params = array();
    protected $declarations = array();
    protected $values = array();

    public function __construct(\Core\System\DiContainer $c)
    {
        //make the container accessable
        $this->c = $c;

        return true;
    }

    public function __set($key,$value)
    {
        $this->values[$key] = $value;
    }

    public function __get($key)
    {
        if(isset($this->values[$key]))
            return $this->values[$key];
        else
            return false;
    }

    protected function dbConnect()
    {
        return true;
    }

    /**
    * Set the name of the package
    *
    * @param string $package_name
    * @return bool
    */
    public function setPackageName($package_name=null)
    {
        isset($package_name)? $this->c->database->package_name = $package_name:'';
        return true;
    }

    /**
    * Set the name of the procedure name
    *
    * @param string $object_name
    * @return bool
    */
    public function setObjectName($object_name=null)
    {
        isset($object_name)? $this->c->database->object_name = $object_name:'';
        return true;
    }

    /**
    * Set the input array to pass into the procedure
    *
    * @param array $input_array
    * @return bool
    */
    public function setInputParams($input_array = NULL)
    {
        if(isset($input_array)) {
            $this->c->database->input_params = $input_array;
        } else {
            $this->c->database->input_params = '';
        }
        return true;
    }

    public function __call($method, $params)
    {
        $a = explode(', ',$params);
    }

    /**
    * Loads the stub data from a JSON file
    *
    * @return array $this->cursor
    */
    public function exec()
    {
        $file = $this->c->server->app_path."/App/Modules/Stubs/".$this->c->database->object_name.'.json';
        if(file_exists($file)){
            $h = fopen($file,"r");
            $data = fread($h,filesize($file));
            fclose($h);
            return json_decode($data);
        } else {
            return array(["No stub data for {$this->c->database->object_name}. Please add JSON file called {$this->c->database->object_name}.JSON in the App/Modules/Stubs/ folder."]);
        }

    }

    public function select($table=false)
    {
        if(!$table)
            return false;

        $this->c->database->object_name = $table;
        return $this->exec();
    }
}