<?php
/**
* Tethys - a PHP 5.4 DI framework
*
* @name MySqlDB.php
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

class OracleDB
{
    protected $c;
    protected $sql;
    protected $query = -1;
	protected $row_count;

    public function __construct(\Core\System\DiContainer $c)
    {
        //make the container accessable
        $this->c = $c;

        //make a connect or reuse the existing one
        $this->dbConnect();

        return true;
    }

    protected function dbConnect()
    {
        if(!isset($this->c->server->db_handle)) {
            //create a new connection
            $this->c->server->db_handle = mysql_connect($this->c->server->db_server,$this->c->server->db_user,$this->c->server->db_pass);
        }

        if(! $this->c->server->db_handle) {
            //if for some reason we do not have a db connect at this point, then throw an exception
            throw new \Exception('Cannot obtain a database handle');
            return false;
        }
		
		if(!mysql_select_db($this->c->server->db_name)){
            //if an invalid database is selected, then throw an exception
            throw new \Exception('Cannot obtain a database handle');
            return false;			
		}
        //DebugBreak();
        return true;
    }

	/**
	 * Escape the SQL string
	 * 
	 * @param $string varchar
	 * @return varchar
	 */
	public function escapeString($string)
	{
	    if(get_magic_quotes_runtime()) $string = stripslashes($string); 
	    return mysql_real_escape_string($string,$this->c->server->db_handle); 
	}

    /**
    * Set the name of the package
    *
    * @param string $package_name
    * @return bool
    */
    public function setPackageName($package_name=null)
    {
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
        return true;
    }

    protected function buildDeclarations()
    {
        return true;
    }
	
	private function free_result()
	{ 
	    if ($this->query!=-1){ 
	        $this->query_id=$query_id; 
	    } 
	    if($this->query!=0 && !mysql_free_result($this->query)){ 
	        throw new \Exception('Cannot free sql reqult set');
        	return false;
	    }
		return true;
	}
	
protected function query()
{ 
    $this->query = mysql_query($this->sql, $this->c->server->db_handle); 

    if (!$this->query){
    	throw new \Exception('Cannot execute database query');
        return false;
    } 
    $this->row_count = mysql_affected_rows($this->c->server->db_handle); 

    return true; 
}
        /**
        * Execute the procedure using the input parameters
        *
        * @return array $this->cursor
        */
        public function exec()
        {
        	$this->query();  //execute the sql
		    $out = [];
					
		    while ($row = $this->fetch($this->query)){ 
		        $out[] = $row; 
		    } 
		
		    $this->free_result(); 
		    return $out; 
        }
}