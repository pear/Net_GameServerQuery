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
 * GameSpy04 Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_GameSpy04 extends Net_GameServerQuery_Protocol
{
    /**
     * Rules packet
     * Status packet
     */
    protected function serverinfo(&$buffer, &$result)
    {
        if ($buffer->read() !== "\x00") {
            return false;
        }
        
        $buffer->read(4);

        if ($buffer->readLast() !== "\x00") {
            return false;
        }

        while (!$buffer->is_empty()) {
            $result->add($buffer->readString(), $buffer->readString());
        }
        
        return $result->fetch();
    }


    /**
     * Player packet
     */
    protected function playerinfo(&$buffer, &$result)
    {
        if ($buffer->read() !== "\x00") {
            return false;
        }
        
        $buffer->read(4);

        if ($buffer->read() !== "\x00") {
            return false;
        }

        $result->addMeta('count', $buffer->readInt8());
        
        // Variable names
        $varnames = array();
        while (!$buffer->is_empty()) {
            $varnames[] = $buffer->readString('_');

            if ($buffer->read() !== "\x00") {
                return false;
            }

            // Look ahead
            if ($buffer->readAhead() === "\x00") {
                $buffer->skip();
                break;
            }
        }
   
        // Loop through sets
        while (!$buffer->is_empty()) {
            foreach ($varnames as $varname) {
                $result->addPlayer($varname, $buffer->readString());
            }      
            
            // Look ahead
            if ($buffer->readAhead() === "\x00") {
                $buffer->skip();
                break;
            } 
        }
        
        // Start all over again to read team information
        $buffer->readInt8();
        
        // Variable names
        $varnames = array();
        while (!$buffer->is_empty()) {
            $varnames[] = $buffer->readString();

            // Look ahead
            if ($buffer->readAhead() === "\x00") {
                $buffer->skip();
                break;
            }
        }

        // Loop through sets
        $i = 0;
        while (!$buffer->is_empty()) {
            foreach ($varnames as $varname) {
                $team[$i][$varname] = $buffer->readString();
            }
            ++$i;
            
            // Look ahead
            if ($buffer->readAhead() === "\x00") {
                $buffer->skip();
                break;
            } 
        }
        
        $result->addMeta('team', $team);

        return $result->fetch();

    }

}

?>