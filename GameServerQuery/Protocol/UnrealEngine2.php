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


require_once NET_GAMESERVERQUERY_BASE . 'Protocol.php';


/**
 * Unreal2 protocol
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Protocol_UnrealEngine2 extends Net_GameServerQuery_Protocol
{
    /*
     * Players
     */
    protected function players(&$buffer, &$result)
    {
        $buffer->skip(4);
        
        // Signifies players packet
        if ($buffer->read() !== "\x02") {
            return false;
        }

        // Parse players
        while ($buffer->getLength()) {
            if (0 === $id = $buffer->readInt32()) {
                // Unreal2XMP Player (ID is always 0)
                // Skip 8 bytes
                $buffer->skip(4);
            } else {
                $result->addPlayer('id', $id);
            }
            
            // Common data
            $result->addPlayer('name',  $this->_readUnrealString($buffer));
            $result->addPlayer('ping',  $buffer->readInt32());
            $result->addPlayer('score', $buffer->readInt32());

            // Stats ID
            $buffer->skip(4);

            // Extra data for Unreal2XMP players
            if ($id === 0) {
                for ($i = 0, $ii = $buffer->readInt8(); $i < $ii; $i++) {
                    $result->addPlayer(
                        $buffer->readPascalString(1),
                        $this->_readUnrealString($buffer)
                    );
                }                
            }
        }

        return $result->fetch();
    }    

    
    
    /*
     * Rules packet
     */
    protected function rules(&$buffer, &$result)
    {
        // Header
        $buffer->skip(4);

        // Signifies rules packet
        if ($buffer->read() !== "\x01") {
            return false;
        }

        // Named values
        $i = -1;
        while ($buffer->getLength()) {
            $varname = $buffer->readPascalString(1);

            // Make sure mutators don't overwrite each other
            if ($varname === 'Mutator') {
                $varname .= ++$i;
            }
            
            $result->add($varname, $buffer->readPascalString(1));
        }

        return $result->fetch();
    }

    
    /*
     * Status packet
     */
    protected function status(&$buffer, &$result)
    {
        // Header
        $buffer->skip(4);

        // Signifies status packet
        if ($buffer->read() !== "\x00") {
            return false;
        }

        $result->add('serverid',    $buffer->readInt32());          // 0
        $result->add('serverip',    $buffer->readPascalString(1));  // empty
        $result->add('gameport',    $buffer->readInt32());
        $result->add('queryport',   $buffer->readInt32());          // 0
        $result->add('servername',  $buffer->readPascalString(1));
        $result->add('mapname',     $buffer->readPascalString(1));
        $result->add('gametype',    $buffer->readPascalString(1));
        $result->add('playercount', $buffer->readInt32());
        $result->add('maxplayers',  $buffer->readInt32());
        $result->add('ping',        $buffer->readInt32());          // 0

        // UT2004 only
        // Check if the buffer contains enough bytes
        if ($buffer->getLength() > 6) {
            $result->add('flags',   $buffer->readInt32());
            $result->add('skill',   $buffer->readInt16());
        }

        return $result->fetch();
    }


    /**
     * Read an UnrealEngine2 string
     *
     * Check which string type it is and return "decoded" string
     *
     * @param       object      $buffer         Buffer object
     * @return      string      The string
     */
    private function _readUnrealString(&$buffer)
    {
        // Normal pascal string
        if (ord($buffer->readAhead(1)) < 129) {
            return $buffer->readPascalString(1);
        }

        // UnrealEngine2 color-coded string
        $length = ($buffer->readInt8() - 128) * 2 - 3;
        $encstr = $buffer->read($length);
        $buffer->skip(3);

        // Remove color-code tags
        $encstr = preg_replace('~\x5e\\0\x23\\0..~s', '', $encstr);

        // Remove every second character
        // The string is UCS-2, this approximates converting to latin-1
        $str = '';
        for ($i = 0, $ii = strlen($encstr); $i < $ii; $i += 2) {
            $str .= $encstr{$i};
        }
        
        return $str;
    }
    
    
    /*
     * Join multiple packets
     *
     * The order does not matter as each packet is "finished".
     * Just join them together and remove extra headers.
     */
    protected function multipacketjoin($packets)
    {
        // Strip the header from all but the first packet
        for ($i = 1, $ii = count($packets); $i < $ii; $i++) {
            $packets[$i] = substr($packets[$i], 5);
        }
        
        return implode('', $packets);
    }
    
}

?>
