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
 * Unreal 2 Engine protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_Unreal2 extends Net_GameServerQuery_Protocol
{
    /**
     * Players packet
     */
    protected function players(&$buffer, &$result)
    {
        // Header
        $buffer->read(4);

        // Packet type
        if ($buffer->read() !== "\x02") {
            return false;
        }

        while ($buffer->getLength() !== 0) {
            $result->addPlayer('id',      $buffer->readInt32());
            $result->addPlayer('name',    $buffer->readPascalString(1));
            $result->addPlayer('ping',    $buffer->readInt32());
            $result->addPlayer('score',   $buffer->readInt32());
            $buffer->read(4);   // stats and team info in ut2004
        }
        
        return $result->fetch();
    }

    
    /**
     * Rules packet
     */
    protected function rules(&$buffer, &$result)
    {
        // Header
        $buffer->read(4);

        // Packet type
        if ($buffer->read() !== "\x01") {
            return false;
        }

        // Var / value strings
        $i = -1;
        while ($buffer->getLength() !== 0) {
            
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

    
    /**
     * Status packet
     */
    protected function status(&$buffer, &$result)
    {
        // Header
        $buffer->read(4);

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
     
}
