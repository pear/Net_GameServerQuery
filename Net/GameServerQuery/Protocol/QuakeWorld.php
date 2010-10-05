<?php
/**
 * PEAR :: Net_GameServerQuery
 *
 * PHP version 4
 *
 * Copyright (c) 1997-2004 The PHP Group
 *
 * This source file is subject to version 3.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_0.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */


require_once NET_GAMESERVERQUERY_BASE . 'Protocol.php';


/**
 * QuakeWorld Protocol
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery

 */
class Net_GameServerQuery_Protocol_QuakeWorld extends Net_GameServerQuery_Protocol
{
    /**
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
        
        while ($buffer->getLength()) {
            $result->add($buffer->readString('\\'),
                         $buffer->readStringMulti(array('\\', "\x0a"), $delimfound));
                
            if ($delimfound === "\x0a") {
                break;
            }
        }
 
        return $result->fetch();
    }
    
    
    /**
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
          
        while ($buffer->getLength()) {
            $result->addPlayer('id', $buffer->readString("\x20"));
            $result->addPlayer('score', $buffer->readString("\x20"));
            $result->addPlayer('time', $buffer->readString("\x20"));
            $result->addPlayer('ping', $buffer->readString("\x20"));
            
            if ($buffer->read() !== '"') {
                return false; 
            }

            $result->addPlayer('nick', $buffer->readString('"'));
            if ($buffer->read() !== "\x20") {
                return false;
            }
            
            if ($buffer->read() !== '"') {
                return false; 
            }
            $result->addPlayer('ipaddr', $buffer->readString('"'));
            if ($buffer->read() !== "\x20") {
                return false;
            }
            
            $result->addPlayer('color_top', $buffer->readString("\x20"));
            $result->addPlayer('color_bottom', $buffer->readString("\x0a"));
        }
        
        return $result->fetch();
    }
}

?>
