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
     * Hold an array of runtime options
     *
     * @var         array
     */
    private $_options;


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
        $this->_options = array('timeout' => 300);

        // Load the config class
        $this->_config = new Net_GameServerQuery_Config;

        // Load the communicate class
        $this->_communicate = new Net_GameServerQuery_Communicate;

        // Load the processing class
        $this->_process = new Net_GameServerQuery_Process ($this->_config);
    }


    /**
     * Set an option
     *
     * @param    string     $option       The option to set
     * @param    string     $value        The value
     */
    public function setOption($option, $value)
    {
        switch ($option):
            case 'timeout':
            case 'normalise':
                $this->_options[$option] = $value;
                break;
            
            default:
                throw new Exception ('Invalid option');
                break;

        endswitch;
    }


    /**
     * Get an option
     *
     * @param    string     $option       The option to get
     */
    public function getOption($option)
    {
        switch ($option):
            case 'timeout':
            case 'normalise':
                return $this->_options[$option];
                break;
            
            default:
                throw new Exception ('Invalid option');
                break;

        endswitch;
    }

    
    /**
     * Validate and process the query list
     *
     * FIXME
     */
    private function _getQueryList($query)
    {
        $querylist = explode('|', $query);

        // Validate each query
        foreach ($querylist as $query) {
            if ($query != 'status' &&
                $query != 'players' &&
                $query != 'rules') {

                throw new Exception ('Invalid Query');
            }
        }

        return $querylist;
    }

    
    /**
     * Map data to the master, communication and processing arrays
     *
     * FIXME
     */
    private function _mapArray ($querylist, $protocol, $game, $addr, $port)
    {
        foreach ($querylist as $query) {
            ++$this->_socketcount;

            // Master list
            $this->_serverlist[$this->_socketcount] = array(
                'servid'    => $this->_counter,
                'query'     => $query
            );

            // Get packet info
            list($packet_name, $packet) = $this->_config->packet($protocol, $query);

            // Data sent to communications class
            $this->_commlist[$this->_socketcount] = array(
                'addr'          => $addr,
                'port'          => $port,
                'packet'        => $packet
            );

            // Data sent the processing class
            $this->_processlist[$this->_socketcount] = array(
                'protocol'      => $protocol,
                'game'          => $game,
                'query'         => $query,
                'packetname'    => $packet_name,
            );
        }
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
        // Check if it's a valid game
        if (false === $this->_config->validgame($game)) {
            throw new Exception ('Invalid Game');
            return false;
        }

        // Incriment the counter
        ++$this->_counter;

        // Find default port
        if ($port === null) {
            $port = $this->_config->queryport($game);
        }

        // Find the protocol
        $protocol = $this->_config->protocol($game);

        // Get list of queries to be sent
        $querylist = $this->_getQueryList($query);

        // Map arrays
        $this->_mapArray($querylist, $protocol, $game, $addr, $port);

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
    public function execute($timeout = null)
    {
        // Check we have something to do
        if ($this->_counter === -1) {
            return false;
        }

        // Set the timeout
        if ($timeout !== null) {
            $this->setOption('timeout', $timeout);
        }

        // Timeout in millseconds
        $timeout = $this->getOption('timeout') * 1000;

        // Communicate with the servers
        // We now have an array of unprocessed server data
        $results = $this->_communicate->query($this->_commlist, $timeout);

        // Finish the array for the process class
        // Add the packets we just recieved into the array
        foreach ($this->_serverlist as $key => $server) {

            // Check if we missed out on any packets
            if (!isset($results[$key])) {
                throw new Exception ('Server did not reply to request');
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