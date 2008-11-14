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
 * @category Pager
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */


require_once NET_GAMESERVERQUERY_BASE . 'Protocol.php';


/**
 * GameSpy Protocol
 *
 * @category Pager
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery
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
            if ($key == 'player_0' || $key == 'final') {
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
    
    
    /*
     * Join multiple packets
     */
    protected function multipacketjoin($packets)
    {
        // Order each packet by the "queryid"
        $newpackets = array();
        foreach ($packets as $packet) {
            $key = substr($packet, strrpos($packet, 'queryid'));
            $packet = substr($packet, 0, strlen($packet) - strlen($key));
            $newpackets[$key{strlen($key) - 1}] = $packet;
        }
        
        // Remove the keys
        ksort($newpackets);
        $newpackets = array_values($newpackets);
        
        // Strip "final" from the last packet
        $last = count($newpackets) - 1;
        $newpackets[$last] = substr($newpackets[$last], 0, strrpos($newpackets[$last], 'final'));
        
        // Remove the leading "/" from each packet
        $packets = array();
        foreach ($newpackets as $key => $packet) {
            if ($key === 0) {
                $packets[] = $packet;
                continue;
            }
            $packets[] = substr($packet, 1);
        }
        
        return implode('', $packets);
    }
    
}

?>
