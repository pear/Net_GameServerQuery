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
    protected function serverinfo(&$response, &$result)
    {
        if ($response->read() !== "\x00") {
            return false;
        }
        
        $response->read(4);

        if ($response->readLast() !== "\x0") {
            return false;
        }

        while ($response->buffer()) {
            $result->add($response->readString(), $response->readString());
        }
        
        return $result->fetch();
    }


    /**
     * Player packet
     */
    protected function playerinfo(&$response, &$result)
    {
        if ($response->read() !== "\x00") {
            return false;
        }
        
        $response->read(4);

        if ($response->read() !== "\x00") {
            return false;
        }

        $result->addMeta('count', $response->readInt8());
        
        // Variable names
        $varnames = array();
        while ($response->buffer()) {
            $varnames[] = $response->readString('_');

            if ($response->read() !== "\x00") {
                return false;
            }

            // Look ahead
            if ($response->read(1, true) === "\x00") {
                $response->read();
                break;
            }
        }
   
        // Loop through sets
        while($response->buffer()) {
            foreach($varnames as $varname) {
                $result->addPlayer($varname, $response->readString());
            }      
            
            // Look ahead
            if ($response->read(1, true) === "\x00") {
                $response->read();
                break;
            } 
        }
        
        // Start all over again to read team information
        if ($response->read() !== "\x02") {
            return false;
        }
        
        // Variable names
        $varnames = array();
        while ($response->buffer()) {
            $varnames[] = $response->readString();

            // Look ahead
            if ($response->read(1, true) === "\x00") {
                $response->read();
                break;
            }
        }

        // Loop through sets
        $i = 0;
        while ($response->buffer()) {
            foreach($varnames as $varname) {
                $team[$i][$varname] = $response->readString();
            }
            ++$i;
            
            // Look ahead
            if ($response->read(1, true) === "\x00") {
                $response->read();
                break;
            } 
        }
        
        $result->addMeta('team', $team);

        return $result->fetch();

    }

}

?>