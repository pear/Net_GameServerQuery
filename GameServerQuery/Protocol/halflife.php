<?php
require_once ('Net/GameServerQuery/Protocol.php');


/**
* Net_GameServerQuery_Protocol_halflife
*
* Half-Life Protocol object for Net_GameServerQuery
*
* @version        1.0
* @package        Net_GameServerQuery
*/
class Net_GameServerQuery_Protocol_halflife extends Net_GameServerQuery_Protocol
{
    /**
    * Our socket holder
    *
    * @var          resource
    * @access       private
    */
    private $_socket;


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
        return sprintf ("\xFF\xFF\xFF\xFF%s\x00", $command);
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
        $this->_socket->send($this->_buildpacket('infostring'));

        // An infostring request does not behave normally.
        // no "count" is returned, so we assume it's all in one packet.

        // This is -1
        $this->_socket->getInt32();

        // This is "infostring"
        $this->_socket->getString(true);

        // This is a backslash "/"
        $this->_socket->getByte();

        // Get the rest of the packet
        $data = $this->_socket->receive();

        // Format it as a standard backslash delimited string
        $info = $this->bds2array($data);

        // If required, normalise the data
        if ($raw === false)
        {
            $info = array(
                        'hostname' => $info['hostname'],
                        'map' => $info['map'],
                        'maxplayers' => $info['max'],
                        'numplayers' => $info['players'],
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

        // This is -1
        $this->_socket->getInt32();
        // This is "j" for ping
        $this->_socket->getChar();
        // Null byte
        $this->_socket->getByte();

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
        $this->_socket->send($this->_buildpacket('players'));

        // This is -1
        $this->_socket->getInt32();

        // This is "D" for players
        $this->_socket->getChar();

        // Number of players in server is the 6th byte
        $numplayers = $this->_socket->getByte();
        
        if ($numplayers == 0) {
            return false; }

        for($i = 0; $i < $numplayers; $i++) {
            // Player ID is the first byte, but we don't care
            $this->_socket->getByte();

            // Player name is the next sequence of bytes until we hit a zero byte
            $players[$i]['name'] = $this->_socket->getString();

            // Frags
            $players[$i]['frags'] = $this->_socket->getInt32();
            
            // Total time in game
            $players[$i]['time'] = round($this->_socket->getFloat32());
        }

        return $players;
    }


    /**
    * Rules
    *
    * @access        public
    * @return        array Array of rules and values
    */
    public function rules ()
    {
        $this->_socket->send($this->_buildpacket('rules'));

        /*
        // The problem with rules is we don't always get the data in a single packet
        // If the identifier is -2, we have multiple packets. If -1, proceed normally.
        $header = $this->_socket->getInt32();

        // We only have a single packet
        if ($header == "-1")
        {
            // This is "E" for rules
            $this->_socket->getChar();

            // Number of rules
            $numrules = $this->_socket->getInt16();

            
            // Loop through and get all the rules
            $rules = array ();
            for($i = 0; $i < $numrules; $i++)
            {
                $key = $this->_socket->getString();
                $value = $this->_socket->getString();
                // Sometimes we have empty values, assume they are 0
                if (empty($value)) { $value = 0; }
                $rules[$key] = $value;
            }

            return $rules;
        }

        // We have multiple packets
        else
        {
            // Grab the first received packet
            // -2 for multipacket
            $this->_socket->getInt32();
            // This is a counter, could be any number
            $this->_socket->getInt32();
            // Grab a proper hexidecimal representation of the 9th byte
            // This is our index byte
            $index = sprintf("%02d", dechex($this->_socket->getByte()));
            $packet_count = $index{1};
            $packet_id = $index{0}+1;
            // Put the packet into an array
            $packets[$packet_id] = $this->_socket->receive();
            
            // Now loop through and repeat for each packet
            for ($i = 1; $i < $packet_count; $i++) {
                $this->_socket->getInt32();
                $this->_socket->getInt32();
                $index = sprintf("%02d", dechex($this->_socket->getByte()));
                $packet_id = $index{0}+1;
                $packets[$packet_id] = $this->_socket->receive();
            }

            // Put the packets in correct order and stick it in the virtual buffer
            ksort($packets);
            $reply = implode('', $packets);
            
            
            
            $this->_socket->set_virtualbuff($reply);

            // This is "E" for rules
            $this->_socket->getChar();


            // Number of rules
            print $numrules = $this->_socket->getInt16();
            
            //print_r (explode("\x00", $this->_socket->read(2000000)));

            
            // Loop through and get all the rules
            $rules = array ();
            for($i = 0; $i < $numrules; $i++)
            {
                $key = $this->_socket->getString();
                $value = $this->_socket->getString();
                // Sometimes we have empty values, assume they are 0
                if (empty($value)) { $value = 0; }
                $rules[$key] = $value;
            }

            return $rules;
        }

        */
        
    }

}
?>