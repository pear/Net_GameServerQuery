<?php
// +----------------------------------------------------------------------+
// | PHP version 4                                                        |
// +----------------------------------------------------------------------+
// | Copyright (c) 1997-2004 The PHP Group                                |
// +----------------------------------------------------------------------+
// | This source file is subject to version 3.0 of the PHP license,       |
// | that is bundled with this package in the file LICENSE, and is        |
// | available through the world-wide-web at the following url:           |
// | http://www.php.net/license/3_0.txt.                                  |
// | If you did not receive a copy of the PHP license and are unable to   |
// | obtain it through the world-wide-web, please send a note to          |
// | license@php.net so we can mail you a copy immediately.               |
// +----------------------------------------------------------------------+
// | Authors: Aidan Lister <aidan@php.net>                                |
// |          Tom Buskens <ortega@php.net>                                |
// +----------------------------------------------------------------------+
//
// $Id$


require_once 'GameServerQuery\Config.php';
require_once 'GameServerQuery\Communicate.php';
require_once 'GameServerQuery\Process.php';
require_once 'GameServerQuery\Convert.php';
require_once 'GameServerQuery\Protocol.php';
require_once 'GameServerQuery\Normalise.php';


/**
 * Query and retrieve information from game servers
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery
{
    /**
     * Hold the counter per server
     *
     * @var         int
     */
    private $_counter;

    /**
     * Hold the counter per socket
     *
     * @var         int
     */
    private $_socketcount;

    /**
     * An instance of the Net_GameServerQuery_Config class
     *
     * @var         object
     */
    private $_config;

    /**
     * An instance of the Net_GameServerQuery_Communicate class
     *
     * @var         object
     */
    private $_communicate;

    /**
     * An instance of the Net_GameServerQuery_Process class
     *
     * @var         object
     */
    private $_process;

    /**
     * A list of the servers added
     *
     * @var         object
     */
    private $_serverlist;

    /**
     * The data sent to the communication class
     *
     * @var         object
     */
    private $_commlist;

    /**
     * The data sent to the processing class
     *
     * @var         object
     */
    private $_processlist;


    /**
     * Constructor
     *
     * Load the classes needed throughout the script
     */
    public function __construct()
    {
        // Initialise counter
        $this->_counter = -1;
        $this->_socketcount = -1;

        // Load the config class
        $this->_config = new Net_GameServerQuery_Config;

        // Load the communicate class
        $this->_communicate = new Net_GameServerQuery_Communicate;

        // Load the processing class
        $this->_process = new Net_GameServerQuery_Process;
    }


    /**
     * Add a server
     *
     * @param    string     $game         The type of game
     * @param    string     $addr         The address to query
     * @param    int        $port         The port to query
     * @param    string     $status       A pipe delimited string of query types
     * @return   int        The number used to identify the server just added
     */
    public function addServer($game, $addr, $port = null, $query = 'status')
    {
        // Incriment the counter
        ++$this->_counter;

        // Find default port
        if (is_null($port)) {
            $port = $this->_config->queryport($game);
        }

        // Find the protocol
        $protocol = $this->_config->protocol($game);

        // Load the protocol class
        require_once "GameServerQuery/Protocol/{$protocol}.php";
        $protocol_classname = "Net_GameServerQuery_Protocol_{$protocol}";
        $protocol_obj = new $protocol_classname;

        // Load the normalise class
        require_once "GameServerQuery/Normalise/{$protocol}.php";
        $normaliser_classname = "Net_GameServerQuery_Normalise_{$protocol}";
        $normaliser_obj = new $normaliser_classname;

        // Get list of queries to be sent
        $querylist = explode('|', $query);

        // Validate each query
        // FIXME

        // Map arrays
        foreach ($querylist as $query) {
            ++$this->_socketcount;
            
            // Master list
            $this->_serverlist[$this->_socketcount] = array(
                'servid'    => $this->_counter,
                'query'     => $query
            );

            // Get packet info
            list($packet_name, $packet) = $this->_config->getPacket($protocol, $query);

            // Data sent to communications class
            $this->_commlist[$this->_socketcount] = array(
                'addr'          => $addr,
                'port'          => $port,
                'packet'        => $packet
            );

            // Data sent the processing class
            $this->_processlist[$this->_socketcount] = array(
                'game'          => $game,
                'query'         => $query,
                'packetname'    => $packet_name,
                'protocol'      => $protocol_obj,
                'normaliser'    => $normaliser_obj
            );
        }

        // Return the counter for identifying the server later
        return $this->_counter;
    }


    /**
     * Execute the query
     *
     * Communicate with the server, then send the information for
     * processing. Then, reconstruct the array so the user can access
     * the data.
     *
     * @param     int        $timeout        The timeout in milliseconds
     * @return    array      An array of server information
     */
    public function execute($timeout = 100)
    {
        // Timeout in millseconds
        $timeout = $timeout * 1000;

        // Communicate with the servers
        // We now have an array of unprocessed server data
        $results = $this->_communicate->query($this->_commlist, $timeout);

        // Finish the array for the process class
        // Add the packets we just recieved into the array
        foreach ($this->_serverlist as $key => $server) {

            // Check if we missed out on any packets
            if (!isset($results[$key])) {
                // If we missed packets, replace with something
                // Not sure what we should do here
                $results[$key] = false;
            }

            $this->_processlist[$key]['packet'] = $results[$key];
        }

        // Process the results
        $results = $this->_process->process($this->_processlist);

        // Put the data back together
        foreach ($this->_serverlist as $key => $server) {
            $servid = $server['servid'];
            $querytype = $server['query'];
            $newresult[$servid][$querytype] = $results[$key];
        }

        // Return
        return $newresult;
    }

}

?>
