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
        // Header
        $gameident = $buffer->read();
        $buffer->skip(3);

        // Packet type
        if ($buffer->read() !== "\x02") {
            return false;
        }

        // Decide on parsing method to use
        if ($gameident == "\x7e") {
            // Unreal2XMP
            $result = $this->_playersXmp(&$buffer, &$result);
        } else {
            // This is for anything else
            $result = $this->_playersStd(&$buffer, &$result);
        }
        
        return $result->fetch();
    }
    
    
    /*
     * Players - Standard
     */
    private function _playersStd(&$buffer, &$result)
    {
        while ($buffer->getLength()) {
            $result->addPlayer('id',      $buffer->readInt32());
            $result->addPlayer('name',    $buffer->readPascalString(1));
            $result->addPlayer('ping',    $buffer->readInt32());
            $result->addPlayer('score',   $buffer->readInt32());

            // Stats and team info in UT2004
            // Not sure how to interpret
            $buffer->skip(4);
        }
        
        return $result;   
    }

    
    /*
     * Players - Unreal2 XMP
     */
    private function _playersXmp(&$buffer, &$result)
    {
        while ($buffer->getLength()) {
            $buffer->skip(8); // XMP Bug (Two I32s of 0)
            $result->addPlayer('name',    $this->_readEncodedString($buffer));
            $result->addPlayer('ping',    $buffer->readInt32());
            $result->addPlayer('score',   $buffer->readInt32());

            // Stats, 0
            $buffer->skip(4);
            
            $count = $buffer->readInt8();
            for ($i = 0; $i < $count; $i++) {
                $result->addPlayer(
                    $buffer->readPascalString(1),
                    $this->_readEncodedString(&$buffer)
                );
            }
        }
        
        return $result;
    }
    
    
    /*
     * Rules packet
     */
    protected function rules(&$buffer, &$result)
    {
        // Header
        $buffer->skip(4);

        // Packet type
        if ($buffer->read() !== "\x01") {
            return false;
        }

        // Var / value strings
        $i = -1;
        while ($buffer->getLength()) {
            $varname = $buffer->readPascalString(1);

            // Make sure mutators don't overwrite each other
            if ($varname === 'Mutator') {
                $varname .= ++$i;
            }
            
            $result->add(
                $varname,
                $buffer->readPascalString(1)
            );
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

        // Packet type
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

        // UT2004 only, so we check if the buffer contains enough bytes
        if ($buffer->getLength() > 6) {
            $result->add('flags',   $buffer->readInt32());
            $result->add('skill',   $buffer->readInt16());
        }

        return $result->fetch();
    }


    /**
     * Read an Unreal2XMP "Type2" string
     *
     * @param       object      $buffer         A buffer object
     * @return      string      The string
     */
    private function _readEncodedString(&$buffer)
    {
        // Check for color coding marker
        if (substr($buffer->readAhead(5), 1) === "\x5e\x00\x23\x00") {           
            $length = ($buffer->readInt8() - 128) * 2;
            $encstr = $buffer->read($length);

            // Remove first 6 chars and last 3
            $encstr = substr($encstr, 6, strpos($encstr, "\0\0\0") - 6);
            
            // Remove all the strange characters
            $str = preg_replace(array('~\^.\#.~', '~[\0-\32]~'), '', $encstr);
        } else {
            // No marker, normal pascal string
            $str = $buffer->readPascalString(1);
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
        foreach ($packets as $key => $packet) {
            if ($key == 0) {
                continue;
            }
           
            $packets[$key] = substr($packet, 5);
        }
        
        return implode('', $packets);
    }
    
}

?>