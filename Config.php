<?php

/**
 * Get protocol config options
 */
class Net_GameServerQuery_Config
{
	/**
	 * A list of all protocol information
	 *
	 * @var			array
	 */
	private static $_protocol;

	/**
	 * A list of all game information
	 *
	 * @var			array
	 */
	private static $_game;    
    
    public function __construct()
    {
		// Load the protocol config
		require 'Protocols.php';
		$this->_protocol = $protocol;

		// Load the game config
		require 'Games.php';
		$this->_game = $game;
    }


	/**
	 * Get the packet to be sent to server
	 *
	 * This should probably be moved somewhere else, I don't know where.
	 *
	 * @return	string	The packet
	 * @param	string	$game	The game
	 * @param	string	$query	The type of query
	 */
	public function getpacket($game, $query)
	{
		$protocol = $this->_game[$game];
		$packetformat = $this->_protocol[$protocol]['packet'];
		$querystring = $this->_protocol[$protocol]['send'][$query];

		$packet = sprintf($packetformat, $querystring);

		return $packet;
	}

}

?>