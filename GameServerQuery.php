<?php
// +----------------------------------------------------------------------+
// | PHP Version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 2 of the PHP license,         |
// | that is bundled with this package in the file LICENSE, and is        |
// | available at through the world-wide-web at                           |
// | http://au.php.net/license/3_0.txt.                                   |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Author: Aidan Lister <aidan@virtualexplorer.com.au>                  |
// +----------------------------------------------------------------------+
//
// $id$
// $revision$

require_once 'PEAR.php';

/**
* Error Constants
*/
define('NET_GAMESERVERQUERY_ERROR_INVALIDGAME',         0);
define('NET_GAMESERVERQUERY_ERROR_PROTOCOLNOTFOUND',    1);
define('NET_GAMESERVERQUERY_ERROR_COULDNOTCONNECT',     2);
define('NET_GAMESERVERQUERY_ERROR_NOREPLY',             3);
define('NET_GAMESERVERQUERY_ERROR_COULDNOTSEND',        4);


/*
* Net_GameServerQuery
*
* @version        1.0
* @package        Net_GameServerQuery
*/
class Net_GameServerQuery
{

    /**
    * Factory Method
    *
    * Creates a connection (socket) object to the server, then loads and returns
    * the protocol object.
    *
    * @version      1.0
    * @param        string $game The game we are talking to
    * @param        string $ip The address to connect to
    * @param        int $port optional The information port of the server, blank for default port
    * @return       object PEAR_Error object on error, Protocol object otherwise
    * @access       public
    */
    public function query ($ip, $port = null, $game)
    {
        // Read our XML file, find the game information
        $xml = simplexml_load_string(file_get_contents('Net/GameServerQuery/Games.xml', true));
        $protobj = $xml->xpath("/games/game[@key=\"$game\"]");

        // If we didn't find any protocol information, unsupported game error
        if (!empty($protobj)) {
            $protocol = $protobj[0]->protocol; }
        else {
            return $this->_throwerror(NET_GAMESERVERQUERY_ERROR_INVALIDGAME); }

        // Make a new connection object
        require_once ('Net/GameServerQuery/Objects/Socket.php');
        $socket = new Net_GameServerQuery_Socket;

        // If we fail to connect, return an error object
        if ($port === null) {
            $port = (int)$protobj[0]->defaultport; }
        if ($socket->connect($ip, $port) === false || empty($ip)) {
            return $this->_throwerror(NET_GAMESERVERQUERY_ERROR_COULDNOTCONNECT); }

        // Check the protocol file exists
        $protocol_file = 'Net/GameServerQuery/Protocols/' . $protocol . '.php';
        if (!$this->file_exists_incpath($protocol_file)) {
            return $this->_throwerror(NET_GAMESERVERQUERY_ERROR_PROTOCOLNOTFOUND); }

        // Load protocol
        require_once ($protocol_file);
        $classname = 'Net_GameServerQuery_Protocol_' . $protocol;

        // Return our new protocol object (Socket object sent as an argument)
        return new $classname ($socket);
    }


    /**
    * Wraparound to throw an error.
    *
    * @version       1.0
    * @access        public
    * @return        string Microtime as a string
    */
    private function _throwerror ($errno)
    {
        include_once 'Net/GameServerQuery/Objects/Error.php';
        return new Net_GameServerQuery_Error ($errno);
    }

    
    /**
    * Return microtime as a single string.
    *
    * @version        1.0
    * @access        public
    * @return        string Microtime as a string
    */
    public function microtime_str()
    { 
        list ($msec, $sec) = explode (" ", microtime()); 
        $microtime = (float)$msec + (float)$sec;
        return $microtime; 
    }


    /**
    * Check if a file exists in the include path
    *
    * @version        1.0
    * @return        bool True if the file exists, False if it does not
    */
    public function file_exists_incpath ($file)
    {
        foreach (explode(';', get_include_path()) as $path)
        {
            $fullpath = $path . '/' . $file;
            if (file_exists($fullpath)) {
                return true; }
        }

        return false;
    }

}

?>