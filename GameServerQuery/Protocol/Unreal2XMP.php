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
 * Unreal 2 XMP protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_Unreal2XMP extends Net_GameServerQuery_Protocol_UnrealTournament03
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

        // Init player id, needed for player properties
        $player_id = 0;

        // Players
        while ($this->_match("(.{4})(.{4})(.)")) {

            // Player number & ID (never updated, bug?)
            $this->_addVar('playernumber', $this->_convert->toInt($this->_result[1], 32));
            $this->_addVar('playerid',     $this->_convert->toInt($this->_result[2], 32));

            // Get player name length and create expression
            $name_length = $this->_convert->toInt($this->_result[3]) - 1;
            $expr = sprintf("(.{%d})\\x00(.{4})(.{4})(.{4})(.)", $name_length);

            if (!$this->_match($expr)) {
                return false;
            }

            $this->_addVar('playername',   $this->_result[1]);
            $this->_addVar('playerping',   $this->_convert->toInt($this->_result[2], 32));
            $this->_addVar('playerscore',  $this->_convert->toInt($this->_result[3], 32));
            $this->_addVar('playerstatid', $this->_convert->toInt($this->_result[4], 32));

            // Get player properties
            $properties_number = $this->_convert->toInt($this->_result[5]));
            for ($i = 0; $i != $properties_number; $i++) {

                // Get property name length
                if (!$this->_match(".")) {
                    return false;
                }
                $name_length = $this->_convert->toInt($this->_result[0]) - 1;

                // Get property name
                $expr = sprintf("(.{%d})(.)", $name_length);
                if (!$this->_match($expr)) {
                    return false;
                }
                $name = $this->_result[1];

                // Get property value length
                $val_length = $this->_convert->toInt($this->_result[2]) - 1;

                // Get property value
                $expr = sprintf(".{%d}", $val_length);
                if (!$this->_match($expr)) {
                    return false;
                }

                // Assign property value to a variable with property name
                $this->_addVar($name . $player_id, $this->_match[0]);
            }

            ++$player_id;
        }
}
?>