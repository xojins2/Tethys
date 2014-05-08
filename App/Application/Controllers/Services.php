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

namespace App\Application\Controllers;

class Services extends \Core\Controller\CoreController
{

    //need to setup the services controller
    //have to figure out
    //constructor
    //json
    //loading dictionaries
    //validation


    public function __construct($controller,$action,$container)
    {
        parent::__construct($controller,$action,$container);
        return true;
    }

    protected function servicesConstructor($public=false, $section)
    {
        $this->parent_section = $section;

        //load the parent constructor
        $this->loadParentConstructor($public);

        //define that this is a service call that uses JSON
        $this->set('is_service', TRUE);
        $this->set('useJson',    TRUE);
        $_SESSION['serviceCall'] = true;
    }

    /**
    * Check if the user has auth rights to the resource
    *
    * @param array $input_array
    */
    public function checkLogin()
    {
        //load the parent constructor
        $this->servicesConstructor(false, 'Index');

        $login_auth = ($this->checkUserAuth()) ? true : false;
        $this->set('login_auth', $login_auth);

        if(!$login_auth) return false;

        //validate the fields
        $this->validateFields();

        //check if we should call the db proc
        $this->checkValidationResults();

        //check to see if there is a database error
        if(!$this->checkDatabaseCode()) {
            //we have a return code so get the message
            $this->set('db_message', $this->{$this->model}->getClientLabel($this->db_return['PO_MESSAGE']));
            return false;
        }

        return true;
    }
}
?>
