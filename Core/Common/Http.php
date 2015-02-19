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

namespace Core\Common;
trait Http
{
    public function getPostVars($key=false) {
        $output = array();
        foreach($_POST as $k=>$value) {
            $output[$k] = filter_input(INPUT_POST,$k,FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if($key) {
            if(isset($output[$key])) {
                return $output[$key];
            } else {
                return false;
            }
        } else {
            return $output;
        }
    }

	/**
	 * get the vars from the get request
	 */
    public function getGetVars($key=false) {
        $output = array();
        foreach($_GET as $k => $value) {
            $output[$k] = filter_input(INPUT_GET,$k,FILTER_SANITIZE_SPECIAL_CHARS);
        }
        if($key) {
            if(isset($output[$key])) {
                return $output[$key];
            } else {
                return false;
            }
        } else {
            return $output;
        }
    }

    /**
    * Get the query string data from the url
    *
    */
    public function getQueryDataByIndex($index=0)
    {
        $get    = getGetVars();
        $output = explodeURL($get[0]);
        if(isset($output['queryString'][0])) {
            $queryParts = explode(',', $output['queryString'][0]);
            return (isset($queryParts[$index]) ? $queryParts[$index] : '');
        } else {
            return '';
        }
    }

    /**
    * Get the query string data from the url
    *
    */
    public function getQueryData()
    {
        $get = getGetVars();
        $output = explodeURL($get[0]);
        if(isset($output['queryString'][0])) {
            return $output['queryString'][0];
        } else {
            return false;
        }
    }

    /**
    * Break up the string into an array that will tell us what the controller,model,action is
    *
    * @param string $url
    * @return array
    */
    public function explodeURL()
    {
        //get the URI
        $url = $_SERVER['REQUEST_URI'];

        //if there is no querystring, then the url is Login
        ($url == '/index.php' || $url == '/Index.php') ? $url = 'Index':'';

        $urlArray = array();
        $url = ltrim($url,'/');
        $urlArray = explode("/",$url);

        //DebugBreak();
        //check to see if the version is set
        if(preg_match('/v[0-9][.]?[0-9]?/',$urlArray[0])){
            $version = $urlArray[0];
            array_shift($urlArray);
        } else
            $version = false;

        $controller = $urlArray[0];

        //check to see if the controller is null and set it to the login page
        (isset($controller)&&$controller!='')?'':$controller='Index';

        array_shift($urlArray);
        $action = isset($urlArray[0])?$urlArray[0]:null;

        //if there is no action, the default action is index
        !isset($action)||$action==""?$action='Index':'';

        array_shift($urlArray);
        $queryString = $urlArray;

        return array('controller'=>$controller,'action'=>$action,'queryString'=>$queryString,'version'=>$version);
    }

    public function checkBrowserVersion()
    {
        //get the visitor's browser type
        $client_browser = (isset($_SERVER['HTTP_USER_AGENT'])) ? $_SERVER['HTTP_USER_AGENT'] : 'Unknows Browser Type';

        if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$client_browser)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($client_browser,0,4))) {
            define('BROWSER_TYPE', 'MOBILE');
        }

        //if we didnt find any mobile, then set a default
        if(!defined('BROWSER_TYPE')) {
            define('BROWSER_TYPE', 'DESKTOP');
        }

        return true;
    }

    public function checkDesktopRedirect()
    {
        // Determine to which site to redirect to (based on requested URL) if DESKTOP browser detected.
        //
        $redirectUrl = HTTP.$_SERVER['SERVER_NAME'];
        $redirectUrl = str_replace('//m', '//', $redirectUrl); // Remove the m. from the mobile url

        define('DESKTOP_URL', $redirectUrl);

        if('DESKTOP' == BROWSER_TYPE) {
            if(isset($_SESSION['no_redirect']) || isset($_GET['no_redirect'])) {
                $_SESSION['no_redirect'] = 1;
            } else {
                header('Location: '.DESKTOP_URL);
                exit(0);
            }
        }
        return true;
    }

    public function redirectUser($url)
    {
        header('Location: '.$url);
        exit(0);
    }

    public function checkMethod()
    {
        $method = $this->getRequestType();
        if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
            if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
                $method = 'DELETE';
            } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
                $method = 'PUT';
            } else {
                ThrowError('Bad header');
            }
        }

        switch($method) {
            case 'DELETE':
            case 'POST':
                $request = $this->getPostVars();
                break;
            case 'PUT':
            case 'GET':
                $request = $this->getGetVars();
                break;
            default:
                ThrowError('Bad Method');
        }

        return $request;
    }

    public function getRequestType()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}

