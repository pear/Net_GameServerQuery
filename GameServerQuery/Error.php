<?php
/**
* Net_GameServerQuery_Error
*
* Error object for Net_GameServerQuery
*
* @version        1.0
* @package        Net_GameServerQuery
*/
class Net_GameServerQuery_Error extends PEAR_Error
{
    /**
    * Error Number
    *
    * @var          string
    * @access       private
    */
    private $_errno;


    /**
    * Constructor
    *
    * @version      1.0
    * @return       constant The error code
    * @access       public
    */
    function __construct($errno)
    {
        $this->_errno = $errno;
    }


    /**
    * Print an error
    *
    * @version      1.0
    * @return       constant The error code
    * @access       public
    */
    function printError()
    {
        $message = "Error in package: Net_GameServerQuery -- %s.";
        
        $errors = array (
            NET_GAMESERVERQUERY_ERROR_INVALIDGAME            => 'Invalid or unsupported game name',
            NET_GAMESERVERQUERY_ERROR_PROTOCOLNOTFOUND       => 'Unable to load protocol file',
            NET_GAMESERVERQUERY_ERROR_COULDNOTCONNECT        => 'Could not connect to specified server',
            NET_GAMESERVERQUERY_ERROR_NOREPLY                => 'Server did not reply to request',
            NET_GAMESERVERQUERY_ERROR_COULDNOTSEND           => 'Could not send packet'
        );
        
        printf($message, $errors[$this->_errno]);
    }

}

?>