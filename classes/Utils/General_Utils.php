<?php
/**
 * Created by PhpStorm.
 * User: itunu.babalola
 * Date: 1/20/17
 * Time: 5:18 PM
 */

namespace Utils;


class General_Utils
{
    private static $instance = null;

    public static function getInstance()
    {
        if(self::$instance == null){
            return self::$instance = new General_Utils();
        }
        return self::$instance;
    }

    //Trying to check if a session is set, if not  return no session
    public function makeSession($key,$value = null)
    {
        //the serialized function converts the actual session data into a format that is storable in the computer
        if(!isset($_SESSION[$key])){
            if($key != null && $value != null){       //if the value of the session is provided ,then save the session
              $_SESSION[$key] = serialize($value);
            }
            //the unserialize function converts the serialized session value back to its normal value
            elseif (($key == null)&&($value = null)&& isset($_SESSION[$key])){
                return  unserialize($_SESSION[$key]);      //if no value is provided and session is set then get back the session
            }
        }
        elseif (isset($_SESSION[$key])){

            return  unserialize($_SESSION[$key]);
        }
        else{
            return false;
        }


    }

    public  function startSession($key,$value){
        session_start();
      return  $_SESSION[$key] = $value;
    }

    public  function isLoggedin()
    {
       return $this->makeSession('loggedin');
    }

}