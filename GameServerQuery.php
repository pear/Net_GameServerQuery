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
     * Hold the counter
     *
     * @var            int
     */
    private $_counter;

    /**
     * The array of servers to query
     *
     * @var            array
     */
    private $_servers;

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
     * Constructor
     */
    public function __construct()
    {
        // Initialise counter
        $this->_counter = -1;

        // Load the config class (this could probably be called something more specific)
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

        // Build the list of packets to be sent
        $querylist = explode('|', $query);
        foreach ($querylist as $query) {
            $querypackets[$query] = $this->_config->packet($game, $query);
        }

        // Default port
        if (is_null($port)) {
            $protocol = $this->_config->protocol($game);
            $port = $this->_config->queryport($protocol);
        }

        // Add information to our servers array
        $this->_servers[$this->_counter] = array(
                    'game'     => $game,
                    'ip'       => $ip,
                    'port'     => $port,
                    'query'    => $querypackets,
                );

        // Return the counter for identifying the server later
        return $this->_counter;
    }


    /**
     * Execute the query
     *
     * @param     int        $timeout        The timeout in milliseconds
     * @return    array      An array of server information
     */
    public function execute($timeout = 60)
    {
        // Timeout in millseconds
        $timeout = $timeout * 1000;
        $result = $this->_communicate->query($this->_servers, $timeout);
        $result = $this->_process->process($result);
        return $result;
    }

}

?>