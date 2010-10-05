<?php
/**
 * PEAR :: Net_GameServerQuery
 *
 * PHP version 4
 *
 * Copyright (c) 1997-2004 The PHP Group
 *
 * This source file is subject to version 3.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_0.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */


define('NET_GAMESERVERQUERY_BASE', dirname(__FILE__) . '/GameServerQuery/');

require_once NET_GAMESERVERQUERY_BASE . 'GameData.php';
require_once NET_GAMESERVERQUERY_BASE . 'Communicate.php';
require_once NET_GAMESERVERQUERY_BASE . 'Process.php';
require_once NET_GAMESERVERQUERY_BASE . 'Error.php';
require_once NET_GAMESERVERQUERY_BASE . 'Config.php';

/**
 * Query and retrieve information from game servers
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */
class Net_GameServerQuery
{
    /**
     * Hold the counter per server
     *
     * @var         int
     */
    private $_servercount = -1;

    /**
     * Hold the counter per socket
     *
     * @var         int
     */
    private $_socketcount = -1;

    /**
     * An instance of the Net_GameServerQuery_Config class
     *
     * @var         object
     */
    private $_gamedata;

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
     * A master list of socket data
     *
     * Contains a mass of information about each socket opened
     * serverid,flag,addr,port,packet,packetname,protocol,game
     *
     * @var         array
     */
    private $_socketlist;

    /**
     * Hold an array of runtime options
     *
     * @var         array
     */
    private $_config;


    /**
     * Constructor
     *
     * Load the classes needed throughout the script
     */
    public function __construct()
    {
        // Set default option values
        $config = new Net_GameServerQuery_Config;
        $config->setOption('normalise', true);
        $config->setOption('showmeta', true);
        $config->setOption('timeout', 100);

        // Load classes
        $this->_config      = $config;
        $this->_gamedata    = new Net_GameServerQuery_GameData;
        $this->_communicate = new Net_GameServerQuery_Communicate;
        $this->_process     = new Net_GameServerQuery_Process($this->_gamedata, $config);
    }


    /**
     * Set an option
     *
     * Wraps around the method in Net_GameServerQuery_Config
     *
     * @param string $option The option to set
     * @param string $value  The value
     *
     * @return void
     */
    public function setOption($option, $value)
    {
        $this->_config->setOption($option, $value);
    }


    /**
     * Get an option
     *
     * Wraps around the method in Net_GameServerQuery_Config
     *
     * @param string $option The option to get
     *
     * @return void
     */
    public function getOption($option)
    {
        $this->_config->getOption($option);
    }

        
    /**
     * Add a server
     *
     * @param string $game  The type of game
     * @param string $addr  The address to query
     * @param int    $port  The port to query
     * @param string $query A pipe delimited string of query types
     *
     * @return   int        The number used to identify the server just added
     */
    public function addServer($game, $addr, $port = null, $query = 'status')
    {
        // Validate game
        if ($this->_gamedata->is_game($game) === false) {
            throw new InvalidGameException;
            return false;
        }

        ++$this->_servercount;

        // Find default port
        if (is_null($port)) {
            $port = $this->_gamedata->getGamePort($game);
        }

        // Find the protocol
        $protocol = $this->_gamedata->getGameProtocol($game);

        // Get list of queries to be sent
        $queryflags = $this->_getQueryFlags($query);

        // Create a list of socket data
        $this->_buildSocketList($queryflags, $protocol, $game, $addr, $port);

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
     * @param int $timeout The timeout in milliseconds
     *
     * @return    array      An array of server information
     */
    public function execute($timeout = null)
    {
        // Ensure there are servers
        if ($this->_servercount === -1) {
            return false;
        }

        // Communicate with the servers
        // Contains an array of unprocessed server data
        $timeout = ($timeout === null) ? $this->getOption('timeout') : $timeout;
        $results = $this->_communicate->query($this->_socketlist, $timeout);

        // Finish the array for the process class
        // Add the packets we just recieved into the array
        foreach ($this->_socketlist as $key => $server) {
            // Check if we missed out on any packets
            if (!isset($results[$key])) {
                $results[$key] = false;
            }

            $this->_socketlist[$key]['response'] = $results[$key];
        }

        // Process the results
        // This calls on the specific protocol files to parse the data
        $results = $this->_process->batch($this->_socketlist);

        // Put the data back together
        foreach ($this->_socketlist as $key => $server) {
            $serverid = $server['serverid'];
            $flag     = $server['flag'];
            if (!isset($newresults[$serverid]['meta'])) {
                $newresults[$serverid]['meta'] = array(
                    'game'      => $server['game'],
                    'addr'      => $server['addr'],
                    'port'      => $server['port'],
                    'gametitle' => $this->_gamedata->getGameTitle($server['game'])
                    );
            }
            $newresults[$serverid][$flag] = $results[$key];
        }
                    
        // Reset all the data arrays for further use
        $this->_reset();
        
        return $newresults;
    }
    
    
    /**
     * Reset everything
     *
     * @return      void
     */
    private function _reset()
    {
        $this->_servercount = -1;
        $this->_socketcount = -1;
        $this->_socketlist  = array();   
    }


    /**
     * Validate and process the query flags
     *
     * @param string $flags A pipe delimited list of query flags
     *
     * @return string
     */
    private function _getQueryFlags($flags)
    {
        $flags = explode('|', $flags);

        // Validate each flag
        foreach ($flags as $flag) {
            if ($flag !== 'status' &&
                $flag !== 'players' &&
                $flag !== 'rules') {

                throw new InvalidFlagException;
            }
        }

        return $flags;
    }

    
    /**
     * Create an array containing all socket data
     *
     * @param string $flags    Query flags  
     * @param string $protocol Protocol
     * @param string $game     The game
     * @param string $addr     The address
     * @param string $port     The port
     *
     * @return void
     */
    private function _buildSocketList($flags, $protocol, $game, $addr, $port)
    {
        // We loop through each of the query flags
        // Each flag gets its own socket
        foreach ($flags as $flag) {
            ++$this->_socketcount;

            list($packetname, $packet) =
                $this->_gamedata->getProtocolPacket($protocol, $flag);

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
