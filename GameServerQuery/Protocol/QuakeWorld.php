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
 * QuakeWorld Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_QuakeWorld extends Net_GameServerQuery_Protocol
{
    /*
     * Rules
     * Status
     */
    protected function status(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read() !== 'n') {
            return false;
        }        
        
        if ($buffer->read() !== '\\') {
            return false;
        }
        
        while (!$buffer->is_empty()) {
            $result->add(
                $buffer->readString('\\'),
                $buffer->readStringMulti(array('\\', "\x0a"), $delimfound)
                );
                
            if ($delimfound === "\x0a") {
                break;
            }
        }
 
        return $result->fetch();
    }
    
    
    /*
     * Players
     */
    protected function players(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read() !== 'n') {
            return false;
        }   
        
        // Ignore all the rules information
        $buffer->readString("\x0a");
        
        if ($buffer->readLast() !== "\x00") {
            return false;
        }
          
        while (!$buffer->is_empty()) {
            $result->addPlayer('id', $buffer->readString("\x20"));
            $result->addPlayer('score', $buffer->readString("\x20"));
            $result->addPlayer('time', $buffer->readString("\x20"));
            $result->addPlayer('ping', $buffer->readString("\x20"));
            
            if ($buffer->read() !== '"') { return false; }
            $result->addPlayer('nick', $buffer->readString('"'));
            if ($buffer->read() !== "\x20") { return false; }
            
            if ($buffer->read() !== '"') { return false; }
            $result->addPlayer('ipaddr', $buffer->readString('"'));
            if ($buffer->read() !== "\x20") { return false; }
            
            $result->addPlayer('color_top', $buffer->readString("\x20"));
            $result->addPlayer('color_bottom', $buffer->readString("\x0a"));
        }
        
        return $result->fetch();
    }
}

?>