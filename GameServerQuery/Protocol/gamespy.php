<?php
require_once ('Net/GameServerQuery/Objects/Protocol.php');


/**
* Net_GameServerQuery_Protocol_gamespy
*
* Game Spy Protocol object for Net_GameServerQuery
*
* @version        1.0
* @package        Net_GameServerQuery
*/
class Net_GameServerQuery_Protocol_gamespy extends Net_GameServerQuery_Protocol
{

    /**
    * Construct
    *
    * @access        public
    * @param        resource $socket The sockect object
    */
    public function __construct (Net_GameServerQuery_Socket $socket)
    {
        $this->_socket = $socket;
    }


    /**
    * Build the packet
    *
    * @access        public
    * @param        string $command The command to send
    * @return        string string The packet
    */
    private function _buildpacket ($command)
    {
        return sprintf ("\\%s\\", $command);
    }


    /**
    * Status
    *
    * @access        public
    * @param        bool $raw True if data should not be normalised
    * @return        array Array of server data
    */
    public function status ($raw = false)
    {
        $this->_socket->send($this->_buildpacket('info'));
        $this->_socket->getChar();
        $info = $this->bds2array($this->_socket->receive());

        // If required, normalise the data
        if ($raw === false)
        {
            $info = array(
                        'hostname' => $info['hostname'],
                        'map' => $info['mapname'],
                        'maxplayers' => $info['maxplayers'],
                        'numplayers' => $info['numplayers'],
                        'password' => $info['password']
                    );
        }

        return $info;
    }


    /**
    * Ping
    *
    * @access        public
    * @return        int Time in milliseconds it took to query the server
    */
    public function ping ()
    {
        $start = Net_GameServerQuery::microtime_str();

        $this->_socket->send($this->_buildpacket('ping'));

        // Useless packet
        $this->_socket->receive();

        $stop = Net_GameServerQuery::microtime_str();

        return round(($stop - $start) * 1000);
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
        $this->_socket->send($this->_buildpacket(''));        
		return false;
    }

}
?>