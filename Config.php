<?php
/**
 * Get protocol config options
 *
 * This needs a more relevant name
 *
 * @category        Net
 * @package         Net_GameServerQuery_Config
 * @author			Aidan Lister <aidan@php.net>
 * @version			$Revision$
 */
class Net_GameServerQuery_Config
{
	/**
	 * An array of all protocol information
	 *
	 * @var			array
	 */
	private static $_protocol;

	/**
	 * An array of all game information
	 *
	 * @var			array
	 */
	private static $_game;


    /**
     * Constructor
     *
     * Load the protocol and games information file
     */
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


    /**
     * Return protocol used by a certain game
     *
     * @return  string  The game used
     * @param   string  $game   The game
     */
    public function getprotocol($game)
    {
        return $this->_game[$game];

    }


    /**
     * Return default query port used by a certain game
     *
     * @return  string  The default query port used
     * @param   string  $game   The game
     */
    public function getdefaultqueryport($game)
    {
        $protocol = $this->_game[$game];
        return $this->_protocol[$protocol]['queryport'];
    }
}

?>