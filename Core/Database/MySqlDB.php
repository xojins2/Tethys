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
*
* Original License and Copyright
* Based off the work of
*  Copyright (C) 2012
*     Ed Rackham (http://github.com/a1phanumeric/PHP-MySQL-Class)
*  Changes to Version 0.8.1 copyright (C) 2013
*    Christopher Harms (http://github.com/neurotroph)
*
*  This program is free software: you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation, either version 3 of the License, or
*  (at your option) any later version.
*
*  This program is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  You should have received a copy of the GNU General Public License
*  along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

namespace Core\Database;

class MySqlDB
{
    // Base variables
    public  $lastError;         // Holds the last error
    public  $lastQuery;         // Holds the last query
    public  $result;            // Holds the MySQL query result
    public  $records;           // Holds the total number of records returned
    public  $affected;          // Holds the total number of records affected
    public  $rawResults;        // Holds raw 'arrayed' results
    public  $arrayedResult;     // Holds an array of the result

    private $databaseLink;      // Database Connection Link

    protected $c;
    protected $sql;
    protected $query = -1;
    protected $row_count;

    public function __construct(\Core\System\DiContainer $c)
    {
        //make the container accessable
        $this->c = $c;

        //make a connect or reuse the existing one
        $this->dbConnect($this->c->database->persistant);

        return true;
    }

    /* *******************
    * Class Destructor  *
    * *******************/

    function __destruct()
    {
        $this->closeConnection();
    }

    /* *******************
    * Private Functions *
    * *******************/

    // Connects class to database
    // $persistant (boolean) - Use persistant connection?
    private function dbConnect($persistant = false)
    {
        //$this->CloseConnection();

        if($persistant){
            $this->c->server->db_handle = mysql_pconnect($this->c->server->db_server,$this->c->server->db_user,$this->c->server->db_pass);
        }else{
            $this->c->server->db_handle = mysql_connect($this->c->server->db_server,$this->c->server->db_user,$this->c->server->db_pass);
        }

        if(!$this->c->server->db_handle){
            $this->lastError = 'Could not connect to server: ' . mysql_error($this->c->server->db_handle);
            return false;
        }

        if(!$this->UseDB()){
            $this->lastError = 'Could not connect to database: ' . mysql_error($this->c->server->db_handle);
            return false;
        }

        $this->setCharset(); // TODO: remove forced charset find out a specific management
        return true;
    }

    // Select database to use
    private function UseDB()
    {
        if(!mysql_select_db($this->c->server->db_name, $this->c->server->db_handle)){
            $this->lastError = 'Cannot select database: ' . mysql_error($this->c->server->db_handle);
            return false;
        }else{
            return true;
        }
    }

    // Performs a 'mysql_real_escape_string' on the entire array/string
    private function SecureData($data, $types)
    {
        if(is_array($data)){
            $i = 0;
            foreach($data as $key=>$val){
                if(!is_array($data[$key])){
                    $data[$key] = $this->CleanData($data[$key], $types[$i]);
                    $data[$key] = mysql_real_escape_string($data[$key], $this->c->server->db_handle);
                    $i++;
                }
            }
        }else{
            $data = $this->CleanData($data, $types);
            $data = mysql_real_escape_string($data, $this->c->server->db_handle);
        }
        return $data;
    }

    // clean the variable with given types
    // possible types: none, str, int, float, bool, datetime, ts2dt (given timestamp convert to mysql datetime)
    // bonus types: hexcolor, email
    private function CleanData($data, $type = '')
    {
        switch($type) {
            case 'none':
                // useless do not reaffect just do nothing
                //$data = $data;
                break;
            case 'str':
            case 'string':
                settype( $data, 'string');
                break;
            case 'int':
            case 'integer':
                settype( $data, 'integer');
                break;
            case 'float':
                settype( $data, 'float');
                break;
            case 'bool':
            case 'boolean':
                settype( $data, 'boolean');
                break;
                // Y-m-d H:i:s
                // 2014-01-01 12:30:30
            case 'datetime':
                $data = trim( $data );
                $data = preg_replace('/[^\d\-: ]/i', '', $data);
                preg_match( '/^([\d]{4}-[\d]{2}-[\d]{2} [\d]{2}:[\d]{2}:[\d]{2})$/', $data, $matches );
                $data = $matches[1];
                break;
            case 'ts2dt':
                settype( $data, 'integer');
                $data = date('Y-m-d H:i:s', $data);
                break;
                // bonus types
            case 'hexcolor':
                preg_match( '/(#[0-9abcdef]{6})/i', $data, $matches );
                $data = $matches[1];
                break;
            case 'email':
                $data = filter_var($data, FILTER_VALIDATE_EMAIL);
                break;
            default:
                $data = '';
                break;
        }
        return $data;
    }
    /* ******************
    * Public Functions *
    * ******************/
    // Executes MySQL query
    public function executeSQL($query)
    {
        $this->lastQuery = $query;
        if($this->result = mysql_query($query, $this->c->server->db_handle)){
            if (gettype($this->result) === 'resource') {
                $this->records  = @mysql_num_rows($this->result);
                $this->affected = @mysql_affected_rows($this->c->server->db_handle);
            } else {
                $this->records  = 0;
                $this->affected = 0;
            }
            $this->c->database->records = $this->records;
            if($this->records > 0){
                $this->arrayResults();
                return $this->arrayedResult;
            }else{
                return true;
            }
        }else{
            $this->lastError = mysql_error($this->c->server->db_handle);
            return false;
        }
    }

    public function commit(){
        return mysql_query("COMMIT", $this->c->server->db_handle);
    }

    public function rollback(){
        return mysql_query("ROLLBACK", $this->c->server->db_handle);
    }

    public function setCharset( $charset = 'UTF8' ) {
        return mysql_set_charset ( $this->SecureData($charset,'string'), $this->c->server->db_handle);
    }

    // Adds a record to the database based on the array key names
    public function insert($table, $vars, $exclude = '', $datatypes)
    {
        // Catch Exclusions
        if($exclude == ''){
            $exclude = array();
        }
        array_push($exclude, 'MAX_FILE_SIZE'); // Automatically exclude this one
        // Prepare Variables
        $vars = $this->SecureData($vars, $datatypes);
        $query = "INSERT INTO `{$table}` SET ";
        foreach($vars as $key=>$value){
            if(in_array($key, $exclude)){
                continue;
            }
            $query .= "`{$key}` = '{$value}', ";
        }
        $query = trim($query, ', ');
        return $this->executeSQL($query);
    }
    // Deletes a record from the database
    public function delete($table, $where='', $limit='', $like=false, $wheretypes)
    {
        $query = "DELETE FROM `{$table}` WHERE ";
        if(is_array($where) && $where != ''){
            // Prepare Variables
            $where = $this->SecureData($where, $wheretypes);
            foreach($where as $key=>$value){
                if($like){
                    $query .= "`{$key}` LIKE '%{$value}%' AND ";
                }else{
                    $query .= "`{$key}` = '{$value}' AND ";
                }
            }
            $query = substr($query, 0, -5);
        }
        if($limit != ''){
            $query .= ' LIMIT ' . $limit;
        }
        return $this->executeSQL($query);
    }

    // Gets a single row from $from where $where is true
    public function select($from, $cols='*', $where='', $wheretypes='', $orderBy='', $limit='', $like=false, $operand='AND')
    {
        //wheretypes can be none, str, int, float, bool, datetime
        // Catch Exceptions
        if(trim($from) == ''){
            return false;
        }
        $query = "SELECT {$cols} FROM `{$from}` WHERE ";
        if(is_array($where) && $where != ''){
            // Prepare Variables
            $where = $this->SecureData($where, $wheretypes);
            foreach($where as $key=>$value){
                if($like){
                    $query .= "`{$key}` LIKE '%{$value}%' {$operand} ";
                }else{
                    $query .= "`{$key}` = '{$value}' {$operand} ";
                }
            }
            $query = substr($query, 0, -(strlen($operand)+2));
        }else{
            $query = substr($query, 0, -6);
        }
        if($orderBy != ''){
            $query .= ' ORDER BY ' . $orderBy;
        }
        if($limit != ''){
            $query .= ' LIMIT ' . $limit;
        }
        return $this->executeSQL($query);
    }

    // Updates a record in the database based on WHERE
    public function update($table, $set, $where, $exclude = '', $datatypes, $wheretypes)
    {
        // Catch Exceptions
        if(trim($table) == '' || !is_array($set) || !is_array($where)){
            return false;
        }
        if($exclude == ''){
            $exclude = array();
        }
        array_push($exclude, 'MAX_FILE_SIZE'); // Automatically exclude this one
        $set     = $this->SecureData($set, $datatypes);
        $where     = $this->SecureData($where,$wheretypes);
        // SET
        $query = "UPDATE `{$table}` SET ";
        foreach($set as $key=>$value){
            if(in_array($key, $exclude)){
                continue;
            }
            $query .= "`{$key}` = '{$value}', ";
        }
        $query = substr($query, 0, -2);
        // WHERE
        $query .= ' WHERE ';
        foreach($where as $key=>$value){
            $query .= "`{$key}` = '{$value}' AND ";
        }
        $query = substr($query, 0, -5);
        return $this->executeSQL($query);
    }

    // 'Arrays' a single result
    public function arrayResult()
    {
        $this->arrayedResult = mysql_fetch_assoc($this->result) or die (mysql_error($this->c->server->db_handle));
        return $this->arrayedResult;
    }

    // 'Arrays' multiple result
    public function arrayResults()
    {
        if($this->records == 1){
            return $this->arrayResult();
        }
        $this->arrayedResult = array();
        while ($data = mysql_fetch_assoc($this->result)){
            $this->arrayedResult[] = $data;
        }
        return $this->arrayedResult;
    }

    // 'Arrays' multiple results with a key
    public function arrayResultsWithKey($key='id')
    {
        if(isset($this->arrayedResult)){
            unset($this->arrayedResult);
        }
        $this->arrayedResult = array();
        while($row = mysql_fetch_assoc($this->result)){
            foreach($row as $theKey => $theValue){
                $this->arrayedResult[$row[$key]][$theKey] = $theValue;
            }
        }
        return $this->arrayedResult;
    }

    // Returns last insert ID
    public function lastInsertID()
    {
        return mysql_insert_id($this->c->server->db_handle);
    }

    // Return number of rows
    public function countRows($from, $where='')
    {
        $result = $this->select($from, $where, '', '', false, 'AND','count(*)');
        return $result["count(*)"];
    }

    // Closes the connections
    public function closeConnection()
    {
        if($this->c->server->db_handle){
            // Commit before closing just in case :)
            $this->commit();
            mysql_close($this->c->server->db_handle);
        }
    }
}
