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
    private $_servercount;

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
     * A list of the socket data,
     * there can be multiple sockets per server
     *
     * @var         object
     */
    private $_socketlist;


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
        // Initialise counters
        $this->_servercount = -1;
        $this->_socketcount = -1;

        // Set default option values
        $this->_options = array('timeout' => 300);

        // Load classes
        $this->_config      = new Net_GameServerQuery_Config;
        $this->_communicate = new Net_GameServerQuery_Communicate;
        $this->_process     = new Net_GameServerQuery_Process ($this->_config);
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
        if ($this->_config->validgame($game) === false) {
            throw new Exception ('Invalid Game');
            return false;
        }

        // Increment the counter
        ++$this->_servercount;

        // Find default port
        if (is_null($port)) {
            $port = $this->_config->getPort($game);
        }

        // Find the protocol
        $protocol = $this->_config->getProtocol($game);

        // Get list of queries to be sent
        $querylist = $this->_getQueryFlags($query);

        // Create a list of socket data
        $this->_buildSocketList($querylist, $protocol, $game, $addr, $port);

        // Return the counter for identifying the server later
        return $this->_servercount;
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
        if ($this->_servercount === -1) {
            return false;
        }

        // Set the timeout
        if (!is_null($timeout)) {
            $this->setOption('timeout', $timeout);
        }

        // Timeout in millseconds
        $timeout = $this->getOption('timeout') * 1000;

        // Communicate with the servers
        // We now have an array of unprocessed server data
        $results = $this->_communicate->query($this->_socketlist, $timeout);

        // Finish the array for the process class
        // Add the packets we just recieved into the array
        foreach ($this->_socketlist as $key => $server) {

            // Check if we missed out on any packets
            if (!isset($results[$key])) {
                throw new Exception ('Server did not reply to request');
            }

            $this->_socketlist[$key]['response'] = $results[$key];
        }

        // Process the results
        $results = $this->_process->process($this->_socketlist);

        // Put the data back together
        foreach ($this->_socketlist as $key => $server) {
            $servid                     = $this->_servercount;
            $flag                       = $server['flag'];
            $newresults[$servid][$flag] = $results[$key];
        }

        // Return
        return $newresults;
    }


    /**
     * Validate and process the query flags
     *
     * @param   string      $query        A pipe delimited list of query flags
     */
    private function _getQueryFlags($flags)
    {
        $flags = explode('|', $flags);

        // Validate each query
        foreach ($flags as $flag) {
            if ($flag !== 'status' &&
                $flag !== 'players' &&
                $flag !== 'rules') {

                throw new Exception ('Invalid Query Flag');
            }
        }

        return $flags;
    }

    
    /**
     * Create an array containing all socket data
     *
     * @param   string      $flags        Query flags  
     * @param   string      $protocol     Protocol
     * @param   string      $game         The game
     * @param   string      $addr         The address
     * @param   string      $port         The port
     */
    private function _buildSocketList($flags, $protocol, $game, $addr, $port)
    {
        // We loop through each of the query flags
        //   because each flag gets its own socket
        foreach ($flags as $flag) {

            ++$this->_socketcount;

            list($packetname, $packet) =
                $this->_config->getPacket($protocol, $flag);

            // Master list
            $this->_socketlist[$this->_socketcount] = array(
                'serverid'   => $this->_servercount,
                'flag'       => $flag,
                'addr'       => $addr,
                'port'       => $port,
                'packet'     => $packet,
                'packetname' => $packetname,
                'protocol'   => $protocol,
                'game'       => $game
                );
            
        };
    }

}
?>
