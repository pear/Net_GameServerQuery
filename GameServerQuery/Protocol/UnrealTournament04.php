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
 * HalfLife Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_UnrealTournament04 extends Net_GameServerQuery_Protocol
{

    /**
     * Players packet
     *
     * @access  protected
     * @return  array      Array containing formatted server response
     */
    protected function _players()
    {
        // Packet id
        if (!$this->_match("\x02")) {
            return false;
        }

        // Players
        while ($this->_match("(.{4})(.)")) {

            // Player id
            $this->_addVar('playerid', $this->_convert->toInt($this->_result[1], 32));
            
            // Get player name length and create expression
            $name_length = $this->_convert->toInt($this->_result[2]) - 1;
            $expr = sprintf("(.{%d})\\x00(.{4})(.{4})(.{4})", $name_length);
            
            // Match expression
            if (!$this->_match($expr)) {
                return false;
            }

            $this->_addVar('playername',  $this->_result[1]);
            $this->_addVar('playerping',  $this->_convert->toInt($this->_result[2], 32));
            $this->_addVar('playerscore', $this->_convert->toInt($this->_result[2], 32));
            $this->_addVar('playerteam',  $this->_convert->toInt($this->_result[2], 32));            
        }

        return $this->_output;
    }


    /**
     * Rules packet
     *
     * @access  protected
     * @return  array      Array containing formatted server response
     */
    protected function _rules()
    {
        // Packet id
        if (!$this->_match("\x01")) {
            return false;
        }

        while ($this->_match(".")) {

            $expr = sprintf("(.{%d})\\x00(.)", ($this->_convert->toInt($this->_result[0]) - 1));
            
            if ($this->_match($expr)) {
                $name = $this->_result[1];
                $expr = sprintf("(.{%d})\\x00", ($this->_convert->toInt($this->_result[2]) - 1));
                if ($this->_match($expr)) {
                    $this->_addVar($name, $this->_result[1]);
                }
                else {
                    return false;
                }
            }
            else {
                return false;
            }
            
        }
    }
    

    /**
     * Status packet
     *
     * @access  protected
     * @return  array      Array containing formatted server response
     */
    protected function _status()
    {
        if (!$this->_match("\\x00(.{4})\\x00(.{4})(.{4})([^\\x00]+)\\x00(.)")) {
            return false;
        }

        $this->_addVar('gameport',  $this->_convert->toInt($this->_result[2], 32));
        $this->_addVar('queryport', $this->_convert->toInt($this->_result[3], 32));
        $this->_addVar('hostname',  $this->_result[4]);

        $expr = sprintf("(.{%d})(.)", ($this->_convert->toInt($this->_result[5]) - 1));
        if (!$this->_match($expr)) {
            return false;
        }

        $this->_addVar('map', $this->_result[1]);

        $expr = sprintf("(.{%d})(.{4})(.{4})", ($this->_convert->toInt($this->_result[2]) - 1));
        if (!$this->_match($expr)) {
            return false;
        }

        $this->_addVar('gametype',   $this->_result[1]);
        $this->_addVar('players',    $this->_convert->toInt($this->_result[2], 32));
        $this->_addVar('maxplayers', $this->_convert->toInt($this->_result[3], 32));

        return $this->_output;

    }


    /**
     * Rules packet
     */ 
    

     
}

?>
