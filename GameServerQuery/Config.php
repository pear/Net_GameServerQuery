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
 * Get protocol config options
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Config
{
    /**
     * An array of all game information
     *
     * @var         array
     */
    private static $_games;


    /**
     * Constructor
     *
     * Load the protocol and games information file
     */
    public function __construct()
    {
        // Load the game config
        require 'Games.php';
        $this->_games = $games;
    }


    /**
     * Return protocol used by a certain game
     *
     * @return  string  The game used
     * @param   string  $game   The game
     */
    public function protocol($game)
    {
        return $this->_games[$game]['protocol'];

    }


    /**
     * Return default query port used by a certain game
     *
     * @return  string  The default query port used
     * @param   string  $game   The game
     */
    public function queryport($game)
    {
        return $this->_games[$game]['queryport'];
    }
}

?>