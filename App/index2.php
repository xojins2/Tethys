<?php
  //php 5.4 demo file

  trait Message_Core
  {
      public function getTestString()
      {
          return "test string";
      }
  }

  trait System_Utils
  {
      public function getEncryptedString($string)
      {
          return $this->setEncryption($string);
      }

      public function getDecryptedString($string)
      {
          return "decrypted text";
      }

      //object must define this method
      abstract public function setEncryption($string);

  }

  class Messaging
  {
      use Message_Core;
      use System_Utils;

      public $value;

      public function __construct()
      {
          $this->value = 101;
          return true;
      }

      public function getMyMessages()
      {
          return $this->value;
          //return $this->getTestString();
      }

      public function setEncryption($string)
      {
          return "asfdasdfasdf";
      }
  }

  $messages = new Messaging();
  echo $messages->getMyMessages();
  echo "<br>";
  echo $messages->getEncryptedString('test');
  echo "<br>";
  echo $messages->getDecryptedString('sdkfjsldkjf');
?>