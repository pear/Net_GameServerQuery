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
 * HalfLife Protocol
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Protocol_HalfLife extends Net_GameServerQuery_Protocol
{
    /**
     * Status
     */
    protected function infostring(&$response, &$result)
    {
        if ($response->readInt32() !== -1) {
            return false;
        }
        
        if ($response->readString() !== 'infostringresponse') {
            return false;
        }

        if ($response->read() !== '\\') {
            return false;
        }

        if ($response->readLast() !== "\x0") {
            return false;
        }

        while ($response->buffer()) {
            $result->add($response->readString('\\'), $response->readString('\\'));
        }

        return $result->fetch();
    }


    /**
     * Players
     */
    protected function players(&$response, &$result)
    {
        if ($response->readInt32() !== -1) {
            return false;
        }

        if ($response->read() !== 'D') {
            return false;
        }

        $result->addMeta('count', $response->readInt8());

        while ($response->buffer()) {
            $result->addPlayer('id',      $response->readInt8());
            $result->addPlayer('name',    $response->readString());
            $result->addPlayer('score',   $response->readInt32());
            $result->addPlayer('time',    $response->readFloat32());
        }

        return $result->fetch();
    }


    /**
     * Rules
     */
    protected function rules(&$response, &$result)
    {
        // Convert multiple packets into a single response
        // Extract the packet order from each packet and order by that
        if (is_array($response->getResponse())) {
            $r = $response->getResponse();
            $packets = array();
            foreach ($r as $packet) {
                $key = substr(bin2hex($packet{8}), 0, 1);
                $packet = substr($packet, 9);
                $packets[$key] = $packet;
            }

            $r = implode('', $packets);
            $response->setResponse($r);
        }

        // Standard parsing
        if ($response->readInt32() !== -1) {
            return false;
        }

        if ($response->read() !== 'E') {
            return false;
        }

        $result->addMeta('count', $response->readInt16());

        while ($response->buffer()) {
            $result->add($response->readString(), $response->readString());
        }

        return $result->fetch();
    }

}

?>