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
 * @todo            Validate the game added
 * @todo            Validate each part of the query type
 */
class Net_GameServerQuery
{
    /**
     * Hold the counter
     *
     * @var            int
     */
    private $_counter;

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
     * These need better names etc.
     */
    private $_commdata;
    private $_recon;
    private $_processdata;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialise counter
        $this->_counter = -1;

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
     * @param    string     $ip           The IP to query
     * @param    int        $port         The port to query
     * @param    string     $status       A pipe delimited string of query types
     * @return   int        The number used to identify the server just added
     */
    public function addServer($game, $ip, $port = null, $query = 'status')
    {
        // Incriment the counter
        ++$this->_counter;

        // Default port
        if (is_null($port)) {
            $protocol = $this->_config->protocol($game);
            $port = $this->_config->queryport($protocol);
        }

        // Build the list of packets to be sent
        $querylist = explode('|', $query);
        foreach ($querylist as $query) {
            $this->_recon[] = array(
                'servid'    => $this->_counter,
                'query'     => $query
                );

            $this->_commdata[] = array(
                'ip'        => $ip,
                'port'      => $port,
                'packet'    => $this->_config->packet($game, $query)
            );
            $this->_processdata[] = array(
                'game'      => $game,
                'query'     => $query
            );
        }

        // Return the counter for identifying the server later
        return $this->_counter;
    }


    /**
     * Execute the query
     *
     * @param     int        $timeout        The timeout in milliseconds
     * @return    array      An array of server information
     */
    public function execute($timeout = 100)
    {
        // Timeout in millseconds
        $timeout = $timeout * 1000;
        $results = $this->_communicate->query($this->_commdata, $timeout);

        // Finish the array for the process class
        foreach ($this->_recon as $key => $server) {

            // Check if we missed out on any packets
            if (!isset($results[$key])) {
                // If we missed packets, replace with something
                // Not sure what we should do here
                $results[$key] = array(0 => false);
            }


            $this->_processdata[$key]['packet'] = $results[$key];
        }

        // Process the results
        $results = $this->_process->process($this->_processdata);

        // Put the data back together
        foreach ($this->_recon as $key => $server) {
            $servid = $server['servid'];
            $querytype = $server['query'];
            $newresult[$servid][$querytype] = $results[$key];
        }

        // Return
        return $newresult;
    }

}

?>