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
 * GameSpy Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_GameSpy extends Net_GameServerQuery_Protocol
{
    /*
     * Status packet
     */
    protected function info(&$buffer, &$result)
    {
        if ($buffer->read() !== '\\') {
            return false;
        }
        
        while ($buffer->getLength()) {
            $key = $buffer->readString('\\');
            if ($key == 'final') {
                break;
            }
            $result->add($key, $buffer->readString('\\'));
        }
        
        return $result->fetch();
    }
    
    
    /*
     * Rules packet
     */
    protected function status(&$buffer, &$result)
    {        
        if ($buffer->read() !== '\\') {
            return false;
        }
        
        while ($buffer->getLength()) {
            $key = $buffer->readString('\\');
            if ($key == 'player_0') {
                break;
            }
            $result->add($key, $buffer->readString('\\'));
        }
        
        return $result->fetch();
    }
    
    
    /*
     * Players packet
     */
    protected function players(&$buffer, &$result)
    {        
        if ($buffer->read() !== '\\') {
            return false;
        }

        while ($buffer->getLength()) {
            $key = $buffer->readString('\\');
            if ($key == 'final') {
                break;
            }            
            list ($key, $id) = explode('_', $key);
            $result->addPlayer($key, $buffer->readString('\\'));
        }

        return $result->fetch();
    }
}

?>