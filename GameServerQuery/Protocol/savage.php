<?php
require_once ('Net/GameServerQuery/Objects/Protocol.php');


/**
* Net_GameServerQuery_Protocol_savage
*
* Game Spy Protocol object for Net_GameServerQuery
*
* @version        1.0
* @package        Net_GameServerQuery
*/
class Net_GameServerQuery_Protocol_savage extends Net_GameServerQuery_Protocol
{

    /**
    * Construct
    *
    * @access        public
    * @param         resource $socket The sockect object
    */
    public function __construct (Net_GameServerQuery_Socket $socket)
    {
        $this->_socket = $socket;
    }


    /**
    * Build the packet
    *
    * @access        public
    * @param         string $command The command to send
    * @return        string string The packet
    */
    private function _buildpacket ($command)
    {
        return sprintf ("%s", $command);
    }


    /**
    * Status
    *
    * @access        public
    * @param         bool $raw True if data should not be normalised
    * @return        array Array of server data
    */
    public function status ($raw = false)
    {
        $this->_socket->send($this->_buildpacket(''));
		return false;
    }


    /**
    * Ping
    *
    * @access        public
    * @return        int Time in milliseconds it took to query the server
    */
    public function ping ()
    {
        $this->_socket->send($this->_buildpacket(''));
		return false;
    }


    /**
    * Players
    *
    * @access        public
    * @return        array Array of player data
    */
    public function players ()
    {
        $this->_socket->send($this->_buildpacket(''));
		return false;
    }


    /**
    * Rules
    *
    * @access        public
    * @return        array Array of rules and values
    */
    public function rules ()
    {
        $this->_socket->send($this->_buildpacket('');        
		return false;
    }

}
?>