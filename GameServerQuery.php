<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
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
// +----------------------------------------------------------------------+
//
// $Id$


require_once 'Config.php';
require_once 'Socket.php';


/**
 * A class to query and retrieve information from game servers
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author			Aidan Lister <aidan@php.net>
 * @version			$Revision$
 */
class Net_GameServerQuery
{
    /**
	 * Hold the counter
	 *
     * @var			int
     */
    private $_counter;

    /**
	 * The array of servers to query
	 *
     * @var			array
     */
    private $_servers;

	/**
	 * A list of all protocol information
	 *
	 * @var			array
	 */
	private $_protocol;

	/**
	 * A list of all game information
	 *
	 * @var			array
	 */
	private $_game;

    private $_config;


    /**
     * Constructor
     */
    public function __construct ()
    {
		// Initialise counter
        $this->_counter = -1;

        // Load the config class
        $this->_config = new Net_GameServerQuery_Config;
	}

    
    /**
     * Add a server
	 *
	 * @param	string	$game		The type of game
	 * @param	string	$ip			The IP to query
	 * @param	int		$port		The port to query
	 * @param	string	$status		A pipe delimited string of query types
	 * @return	int		The counter
     */
    public function addServer ($game, $ip, $port = null, $query = 'status')
    {
		// Incriment the counter
		++$this->_counter;

		// Build the list of packets to be sent
		$querylist = explode('|', $query);
		foreach ($querylist as $query) {
			$querypackets[$query] = $this->_config->getpacket($game, $query);
		}

		// Default port
		if (is_null($port)) {
			$port = $this->_protocol[$this->_game[$game]]['queryport'];
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
	 * @param	int		$timeout		The timeout in milliseconds
	 * @return	array	An array of server information
     */
    public function execute ($timeout = 300)
    {
		// Timeout in millseconds
		$timeout = $timeout * 1000;
		
		$socket = new Net_GameServerQuery_Socket;
        $result = $socket->go($this->_servers, $timeout);

        return $result;
    }

}

?>