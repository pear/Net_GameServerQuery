<?php
/**
* Net_GameServerQuery_Protocol
*
* The abstract class for all Net_GameServerQuery protocol objects
*
* @version        1.0
* @package        Net_GameServerQuery
*/
abstract class Net_GameServerQuery_Protocol implements Net_GameServerQuery_Protocol_Interface
{   
    
    /**
    * Our socket holder
    *
    * @var          resource
    * @access       private
    */
    private $_socket;


    /**
    * Turn a backslash delimited string into an assoc array
    *
    * @access        public
    * @version        1.0
    */
    public function bds2array ($string)
    {
        $data_arr = explode ('\\', $string);

        for ($i = 0, $x = count($data_arr); $i < $x; $i += 2) {
            $info[$data_arr[$i]] = $data_arr[$i+1]; }

        return $info;
    }
}


/**
* Net_GameServerQuery_Protocol_Interface
*
* The interface for all Net_GameServerQuery protocol objects
*
* @version        1.0
* @package        Net_GameServerQuery
*/
interface Net_GameServerQuery_Protocol_Interface
{
    public function status ();
    public function ping ();
    public function players ();
    public function rules ();
}
?>