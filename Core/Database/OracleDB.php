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

class OracleDB
{
    protected $c;
    protected $package_name;
    protected $object_name;
    protected $input_params = array();
    protected $declarations = array();

    public function __construct(\Core\System\DiContainer $c)
    {
        //make the container accessable
        $this->c = $c;

        //make a connect or reuse the existing one
        $this->dbConnect();

        //create an empty parameter array
        $this->c->database->input_params = [];
        return true;
    }

    protected function dbConnect()
    {
        if(!isset($this->c->server->db_handle)) {
            //create a new connection
            $this->c->server->db_handle = oci_connect($this->c->server->db_user,$this->c->server->db_pass,$this->c->server->db_name);

        }

        if(! $this->c->server->db_handle) {
            //if for some reason we do not have a db connect at this point, then throw an exception
            throw new \Exception('Cannot obtain a database handle');
            return false;
        }
        //DebugBreak();
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

    protected function buildDeclarations()
    {
        //get the package name and procedure
        //lets get all the arguments from the proc
        $sql = "
        SELECT
        a.ARGUMENT_NAME, a.POSITION, a.DATA_TYPE, a.DATA_LENGTH
        FROM
        user_arguments a,
        user_objects   o
        WHERE
        DATA_LEVEL     = 0                      AND
        a.object_id    = o.object_id            AND
        o.object_name  = '{$this->c->database->package_name}' AND
        a.object_name  = '{$this->c->database->object_name}'";

        //execute the sql and get the results
        $r = OCIParse($this->c->server->db_handle,$sql);

        ociexecute($r);

        ocifetchstatement($r, $values, null, null, OCI_FETCHSTATEMENT_BY_ROW);

        foreach($values as $row) {
            if($row['ARGUMENT_NAME']) {
                $type  = ($row['DATA_TYPE'] == "REF CURSOR") ? "OCI_B_CURSOR" : $row['DATA_TYPE'];
                $len   = ($row['DATA_LENGTH'] == 0) ? 255 : $row['DATA_LENGTH'];

                $this->declarations[$row['POSITION']]['name'] = ":".$row['ARGUMENT_NAME'];
                $this->declarations[$row['POSITION']]['var']  = $row['ARGUMENT_NAME'];
                $this->declarations[$row['POSITION']]['len']  = $len;
                $this->declarations[$row['POSITION']]['type'] = $type;
                unset($type);
                unset($len);
            }
        }

        return true;
    }

        /**
        * Execute the procedure using the input parameters
        *
        * @return array $this->cursor
        */
        public function exec()
        {
            //init vars
            $declaration_names      = '';
            $this->curs_count                  = 0;
            $this->curs = array();
            $this->c->debug->db = array();
            //(!isset($this->c->database->input_params))?$this->c->database->input_params=[]:null;

            //build the declairations
            $this->buildDeclarations();

            //sort the declarations
            ksort($this->declarations);

            //loop through the declarations and build the variable to be used in the sql statement
            foreach ($this->declarations as $value) {
                $declaration_names .= $value['name'].",";
            }
            $declaration_names = substr($declaration_names, 0, -1);  //remove the last comma

            //parse the procedure - make sure everything is set
            if((isset($this->c->database->package_name)||$this->c->database->package_name!='') && (isset($this->c->database->object_name)||$this->c->database->object_name!='')){
                $this->stmt = oci_parse($this->c->server->db_handle,"begin {$this->c->database->package_name}.{$this->c->database->object_name}({$declaration_names}); end;");
            } else {
                //something is not set, throw an exception
                die ('Can not parse the package: '.$this->c->database->package_name.' procedure: '.$this->c->database->object_name);
            }

            //loop through the declarations and build the variable to bind your vars
            foreach ($this->declarations as $key => $value) {
                if(substr($value['name'], -4) == '_TAB') {
                    $temp_collection = oci_new_collection($this->c->server->db_handle, 'TYP_VARCHAR2_TAB');
                    oci_bind_by_name($this->stmt,$value['name'],$temp_collection,-1,SQLT_NTY);

                    if(isset($this->c->database->input_params[$value['var']])) {
                        $temp_array = $this->c->database->input_params[$value['var']];
                        foreach($temp_array as $value2) {
                            $temp_collection->append($value2);
                        }
                    }
                    ${$value['var']} = $temp_collection;
                } elseif(substr($value['name'], -4) == '_ARR') {
                    $temp_collection1 = oci_new_collection($this->c->server->db_handle, 'TBL_PROJ');
                    $temp_collection2 = oci_new_collection($this->c->server->db_handle, 'TYP_PROJ');
                    oci_bind_by_name($this->stmt,$value['name'],$temp_collection1,-1,SQLT_NTY);

                    if(isset($this->c->database->input_params[$value['var']])) {
                        $temp_array = $this->c->database->input_params[$value['var']];
                        foreach($temp_array as $value2) {
                            foreach($value2 as $value3){
                                $temp_collection2->append($value3);
                            }
                            $temp_collection1->assign($temp_collection2);
                        }
                    }
                } elseif(($value['type']) == "OCI_B_CURSOR") {
                    (isset(${$value['var']}))?$this->curs[$this->curs_count] = ${$value['var']}:'';
                    $this->curs[$this->curs_count] = oci_new_cursor($this->c->server->db_handle);
                    oci_bind_by_name($this->stmt,$value['name'],$this->curs[$this->curs_count],$value['len'],OCI_B_CURSOR) or die ('Can not bind variable');
                    $this->curs_count++;
                } else {
                    if(array_key_exists($value['var'], $this->c->database->input_params) && $this->c->database->input_params[$value['var']] != "") {

                        if(substr($value['name'], -5) == '_BLOB'){
                            $rlob = oci_new_descriptor($this->c->server->db_handle,OCI_D_LOB);
                            oci_bind_by_name($this->stmt,$value['name'],$rlob,-1,OCI_B_BLOB) or die ('Can not bind variable');
                            $rlob->WriteTemporary ($this->c->database->input_params[$value['var']], OCI_TEMP_BLOB);

                        } else if(substr($value['name'], -5) == '_CLOB'){
                            $rlob = oci_new_descriptor($this->c->server->db_handle,OCI_D_LOB);
                            oci_bind_by_name($this->stmt,$value['name'],$rlob,$value['len'],OCI_B_CLOB) or die ('Can not bind variable');
                            $rlob->WriteTemporary ($this->c->database->input_params[$value['var']]);

                        } else {
                            oci_bind_by_name($this->stmt,$value['name'],$this->c->database->input_params[$value['var']],$value['len']) or die ('Can not bind variable');
                        }
                    } else {
                        if(substr($value['name'], -5) == '_BLOB'){
                            $rlob = oci_new_descriptor($this->c->server->db_handle,OCI_D_LOB);
                            oci_bind_by_name($this->stmt,$value['name'],$rlob,-1,OCI_B_BLOB) or die ('Can not bind variable');
                            if(isset(${$value['var']}))
                                $rlob->WriteTemporary (${$value['var']}, OCI_TEMP_BLOB);

                        }else if(substr($value['name'], -5) == '_CLOB'){
                            $rlob = oci_new_descriptor($this->c->server->db_handle,OCI_D_LOB);
                            oci_bind_by_name($this->stmt,$value['name'],$rlob,$value['len'],OCI_B_CLOB) or die ('Can not bind variable');
                            if(isset(${$value['var']}))
                                $rlob->WriteTemporary (${$value['var']});
                        } else {
                            oci_bind_by_name($this->stmt,$value['name'],${$value['var']},$value['len']) or die ('Can not bind variable');
                        }
                        (substr($value['var'], 0, 2) == "PO") ? $outarray[] = $value['var'] : '';
                    }
                }
                (isset($value['name'])) ? $dataname = $value['name'] : $dataname = '';
                (isset($this->c->database->input_params[$value['var']])) ? $dataval = $this->c->database->input_params[$value['var']] : $dataval = '';
                (isset($value['len']))?$datalen   = $value['len']:$datalen='';
                (isset($value['type']))?$datatype = $value['type']:$datatype='';
                //$this->c->debug->db[$this->c->database->package_name.'_'.$this->object_name][]=array($key,$dataname,$dataval,$datalen,$datatype); //set a global var accessable in tamsUtils->generateError
            }

            //execute the statements
            oci_execute($this->stmt);   // or die ('Can not Execute statment');

            // closing the temp object if exists
            if(isset($rlob)) {
                $rlob->close();
            }

            //$this->c->debug->db_return_code = $this->output_params['PO_RETURNCODE'];     //get the return code

            //check to see if the cursor has be set by the proc or do nothing
            if($this->curs_count > 0) {
                $i = 0;
                foreach($this->curs as $this->curs_value){
                    oci_execute($this->curs_value);

                    //if($this->return_type == 'COLUMN') {
                    //    oci_fetch_all($this->curs_value, $this->cursor[$i], null, null, OCI_FETCHSTATEMENT_BY_COLUMN);
                    //} else {
                        oci_fetch_all($this->curs_value, $this->cursor[$i], null, null, OCI_FETCHSTATEMENT_BY_ROW);
                    //}
                    $i++;
                }
                //check to see if we only have one element in the array (for backward compatibility)
                if(count($this->cursor) == 1) {
                    $this->cursor = $this->cursor[0];
                }
            }

            unset($this->curs);
            unset($outarray);
            unset($this->declarations);

            //oci_close($this->c->server->db_handle);
            //isset($this->stmt)                         ? oci_free_statement($this->stmt) : '';
            //isset($this->curs_value) && !is_int($this->curs_value) ? oci_free_statement($this->curs_value) : '';
            isset($temp_collection)                    ? $temp_collection->free()        : '';
            isset($temp_collection1)                   ? $temp_collection1->free()       : '';
            isset($temp_collection2)                   ? $temp_collection2->free()       : '';

            //if the cursor is set, return it, or check the return code and return false on proc error
            return isset($this->cursor) ? $this->cursor : true; //($this->c->debug->db_return_code != 0 ? FALSE : TRUE);
            //return true;
        }
}