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

class Element
{
    /**  Location for overloaded data.  */
    private $data = array();

    /**
    * sets all the properties of the class
    *
    * @param mixed $array
    */
    public function setElements($array)
    {
        for($i=0;$i<count($array);$i++){
            $this->$array[$i] = $this->setElementOptions($array[$i]);

        }
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public static function setElementOptions($array)
    {
        //this is where we will inspect the data dictionary for the element
        //and set all the defaults
        /**
        'DefaultValue' => 'The default value of the element',
        'RenameTo' => 'The label to use with the field',
        'Display' => 'Should this field be displayed',
        'EnableEdit' => 'Is this field editable',
        'Width' => 'The width of the field',
        'ArgumentName' => 'THe procedure input parameter',
        'DataType' => 'The type of data in this field - used by formatting',
        'Entity' => 'The input type to use for this field',
        'elementArray' => 'Is this field an array',
        'defaultDisabled' => 'Should this field be disabled by default',
        'addClass' => 'Add this extra class to the style',
        'Lookup' => array(
        'SecondaryKey'=>'The key to use as the secondary value',
        'Package' => 'Name of the package to get the data from',
        'Procedure' => 'name of the procedure to use',
        'Sort' => 'key to use to sort the data',
        'ID' => 'key to use as the ID',
        'Label' => array (
        0 => 'Key to use as the label',
        1 => 'Second key to use as the label',
        ),
        ),
        'Validation' => false,
        'data' => 'This contains the input data',
        'MultiSelect'=>'Is this a multi-select element',
        'ShowSelectOne'=>'Should we show the Select One first',
        'UseBR'=>'Add a BR between each item',
        'Spaces'=>'The number of spaces to use between each item',
        *
        */

        $value = array();

        //Basic Data Dictionary Items
        $value['validation_rules']         =false;
        $value['visibility_rules']['data_element']               =false;

        $value['form_properties']=false;
        $value['form_properties']['rename_to']           =false;
        $value['form_properties']['default_value']       =false;
        $value['Fieldset']           =false;
        $value['FieldsetOrder']      =false;
        $value['Width']              =false;
        $value['MultiSelect']        =false;
        $value['ShowSelectOne']      =false;
        $value['UseBR']              =false;
        $value['Spaces']             =false;
        $value['Cols']               =false;
        $value['Rows']               =false;
        $value['TabIndex']           =false;
        $value['Title']              =false;
        $value['WrapOutput']         =false;
        $value['DefaultHidden']      =false;
        $value['Link']               =false;
        $value['addClass']           =false;

        $value['visibility_rules']   =false;
        $value['disallow_role']      =false;
        $value['DisableEdit']        =false;
        $value['EnableEdit']         =false;

        $value['fileter_rules']      =false;

        $value['cursor_key']         =false;
        $value['argument_name']      =false;
        $value['data_lookup']        =false;
        $value['DataType']           =false;
        $value['Explode']            =false;
        $value['SyncWith']           =false;
        $value['Function']           =false;
        $value['CopyRow']            =false;
        $value['ColCountOverride']   =false;
        $value['Dialog']             =false;
        $value['lookup_rules']             =false;
        $value['divInputClass']      =false;
        $value['divLabelClass']      =false;
        $value['Entity']             =false;
        $value['elementArray']       =false;
        $value['defaultDisabled']    =false;
        $value['data']               =false;
        $value['AltAttributes']      =false;

        //Lookup setttings
        $value['lookup_rules']['ID']                   = false;  //the form element ID
        $value['lookup_rules']['Label']                = false;  //the form element label
        $value['lookup_rules']['Function']             = false;  //the function to get the data
        $value['lookup_rules']['Formatting']           = false;  //how the form element should be rendered
        $value['lookup_rules']['UseBR']                = false;  //use br in radio groups?
        $value['lookup_rules']['Sort']                 = false;  //how to sort the data
        $value['lookup_rules']['Size']                 = false;  //how to sort the data
        $value['lookup_rules']['OtherData']            = false;  //used to build the optgroup for select elements
        $value['lookup_rules']['OtherID']              = false;  //used to build the optgroup for select elements
        $value['lookup_rules']['OtherDefaultValue']    = false;  //used to build the optgroup for select elements
        $value['lookup_rules']['Filter']               = false;  //user to filter the $_POST var
        $value['lookup_rules']['DefaultSelectedPost']  = false;  //the form element default value from POST
        $value['lookup_rules']['DefaultSelectedValue'] = false;  //the form element default value
        $value['lookup_rules']['ElementArray']         = false;  //make the form element an array of values
        $value['lookup_rules']['Package']              = false;  //make the form element an array of values
        $value['lookup_rules']['Procedure']            = false;  //make the form element an array of values
        $value['lookup_rules']['UseJSTreeCheckBox']    = false;  //make the form element an array of values
        $value['lookup_rules']['DelayedLoad']          = false;  //make the form element an array of values
        $value['lookup_rules']['ColumnHide']           = false;  //make the form element an array of values
        $value['lookup_rules']['InputParams']          = false;  //make the form element an array of values
        $value['lookup_rules']['DefaultSelectedPost']   = false;

        //custom function settings
        $value['Function']['Name']                = false;  //the form element ID
        $value['Function']['Data']                = false;  //the form element label

        //validation settings
        $value['Required'] = false;
        $value['Validation']['minlength']       = false;
        $value['Validation']['maxlength']       = false;
        $value['Validation']['min']             = false;
        $value['Validation']['max']             = false;
        $value['Validation']['date']            = false;
        $value['Validation']['number']          = false;
        $value['Validation']['digits']          = false;
        $value['Validation']['optioncount']     = false;
        $value['Validation']['custom']          = false;

        //is this an element array
        $value['elementArray'] = false;

        //should this be disabled
        $value['defaultDisabled'] = false;

        //should this be a multiselect
        $value['MultiSelect'] = false;

        //add any extra css class
        $value['addClass'] = false;

        //start the option list
        $value['ShowSelectOne'] = false;

        return $value;
    }

    protected static function SO($a, $b)
    {
        if(isset($a[$this->strcompKey]) && isset($b[$this->strcompKey])) {
            return (strcmp ($a[$this->strcompKey], $b[$this->strcompKey]));
        } else {
            return false;
        }
    }

    /**
    * Pass in 2 arrays to make the label
    * $array1 is all your data
    * $array2 is the lookup label array
    *
    * @param array $array1
    * @param array $array2
    */
    protected static function createElementLabel($array1,$array2)
    {
        $label = '';

        if($array2){
            foreach($array2 as $value) {
                //loop through the label array to build the displayed labels
                $display = (isset($array1[$value]))?$array1[$value]:'';
                $label .= " ".$display.",";
            }
            $label = substr($label,0,-1);     //remove the last comma
        } else {
            $label = $array1;
        }

        return $label;
        //return $array1;
    }

    protected static function wrapOutput($label=null,$input=null, $required=false, $class1='', $class2='')
    {
        $required_flag = ($required?"*":"");
        $output  = "<div class='field_label $class2'>$label <span class='required_flag'>$required_flag</span></div>";
        $output .= "<div class='field_input $class1'>$input</div>";
        return $output;
    }

    protected static function wrapOutputLabel($label = null, $required = false,  $class = '')
    {
        $required_flag = ($required ? "*" : "");
        $output = "<div class='field_label $class'>$label <span class='required_flag'>$required_flag</span></div>";
        return $output;
    }

    protected static function wrapOutputInput($input = null, $class = '')
    {
        $output = "<div class='field_input $class'>$input</div>";
        return $output;
    }

    /**
    * Internal function to set all the html element variables for the render method
    *
    * @param mixed $array
    */
    protected static function setElementVars($array = array())
    {
        $output = array();

        //get the key to use for the option group
        $output['otherData'] = $array['Lookup']['OtherData'];
        $output['otherID'] = $array['Lookup']['OtherID'];

        //get the key to use to sort the data
        $output['sort'] = $array['Lookup']['Sort'];

        //get the key to use to sort the data
        $output['id'] = $array['Lookup']['ID'];

        //make a copy of the data
        $output['data'] = $array['data'];

        return $output;
    }
}