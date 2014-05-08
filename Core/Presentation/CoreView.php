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

    namespace Core\Presentation;

    class CoreView
    {

      protected $c;

      public function __construct(\Core\System\DiContainer $c)
      {
          $this->c = $c;
          return true;
      }

      protected function loadTemplate($type=false)
      {
          if(!$type)
            return false;

          $file = $this->c->server->app_path.$this->c->settings->template_path.$type;
          $h = fopen($file,"r");
          $this->c->output->header = fread($h,filesize($file));
          return true;
      }

      //might not be needed
      protected function loadHeader()
      {
          $this->loadTemplate($this->c->settings->header_template);
          return true;

          /*
          $file = $this->c->server->app_path.$this->c->settings->template_path.$this->c->settings->header_template;
          $h = fopen($file,"r");
          $this->c->output->header = fread($h,filesize($file));
          return true;
          */
      }

      //might not be neeeded
      protected function loadFooter()
      {
          $this->loadTemplate($this->c->settings->footer_template);
          return true;

          /*
          $file = $this->c->server->app_path.$this->c->settings->template_path.$this->c->settings->footer_template;
          $h = fopen($file,"r");
          $this->c->output->footer = fread($h,filesize($file));
          return true;
          */
      }

      protected function loadBody()
      {
          $this->loadTemplate($this->c->settings->action_template);
          return true;

          /*
          //get the template to use
          $file = $this->c->server->app_path.$this->c->settings->template_path.$this->c->settings->action_template;

          //open the file and read it
          $h = fopen($file,"r");
          $this->c->output->body = fread($h,filesize($file));

          //this is from the elements
          //$this->c->output->body .= $this->c->html;

          return true;
          */
      }

      protected function getFileTags()
      {
          //replace all the tags
          preg_match_all($this->c->settings->template_tag_regex, $this->c->output->body, $matches);

          return true;
      }
    }
