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


/**
 * API for accessing stored protocol and game information
 *
 * Provides an abstraction for accessing the stored protocol data and
 * game information.
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_GameData
{
    /**
     * An array of all game information
     *
     * @var         array
     */
    private static $_games;

    /**
     * An array of all packet information
     *
     * @var        array
     */
    private static $_packets;

    /**
     * An array of all normal information
     *
     * @var        array
     */
    private static $_normals;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Load the games information
        require NET_GAMESERVERQUERY_BASE . 'Games.php';

        // Store it
        $this->_games   = $games;
        $this->_packets = $packets;
        $this->_normals = $normals;
    }


    /**
     * Test if game is registered
     *
     * @param       string      $game           The game
     * @return      string      TRUE if the game is valid else FALSE
     */
    public function is_game($game)
    {
        if (isset($this->_games[$game])) {
            return true;
        }

        return false;
    }


    /**
     * Test if protocol is registered
     *
     * @param       string      $protocol       The protocol
     * @return      bool        TRUE if the protocol is valid else FALSE
     */
    public function is_protocol($protocol)
    {
        if (isset($this->_games[$game])) {
            return true;
        }

        return false;
    }


    /**
     * Return packet used by a certain protocol
     *
     * @param       string      $protocol       The protocol
     * @param       string      $type           The packet type
     * @return      array       The packet used
     */
    public function getProtocolPacket($protocol, $type)
    {
        if (isset($this->_packets[$protocol][$type])) {
            return $this->_packets[$protocol][$type];
        }
        
        return false;
    }
    

    /**
     * Get game name
     *
     * @param       string      $game           The game
     * @return      string      The game name
     */
    public function getGameTitle($game)
    {
        if (isset($this->_games[$game]['title'])) {
            return $this->_games[$game]['title'];
        }
        
        return false;
    }
    
    
    /**
     * Return normal keys
     *
     * @param       string      The protocol
     * @return      array       The normals  
     */
    public function getProtocolNormals($protocol = 0)
    {
        return $this->_normals[$protocol];
    }
    
    
    /**
     * Return protocol used by a certain game
     *
     * @return      string      The game used
     * @param       string      $game           The game
     */
    public function getGameProtocol($game)
    {
        return $this->_games[$game]['protocol'];
    }


    /**
     * Return default query port used by a certain game
     *
     * @return      string      The default query port used
     * @param       string      $game           The game
     */
    public function getGamePort($game)
    {
        return $this->_games[$game]['queryport'];
    }

}

?>