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
    protected function infostring(&$response)
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

        while ($response->bufferHasData()) {
            $response->addResult($response->readString('\\'), $response->readString('\\'));
        }

        return $response->getResult();
    }

    protected function players(&$response)
    {
        if ($response->readInt32() !== -1) {
            return false;
        }

        if ($response->read() !== 'D') {
            return false;
        }

        $response->addMeta('count', $response->readInt8());

        while ($response->bufferHasData()) {
            $response->addPlayer('id',      $response->readInt8());
            $response->addPlayer('name',    $response->readString());
            $response->addPlayer('score',   $response->readInt32());
            $response->addPlayer('time',    $response->readFloat32());
        }

        return $response->getResult();
    }

    protected function rules(&$response)
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

        // Get header and rulecount
        if ($response->match("\xFF\xFF\xFF\xFF\x45(.{2})")) {
            $response->addMeta('rulecount', $response->toInt($response->getMatch(1), 16));
        } else {
            return false;
        }

        // Variable / value pairs
        while ($response->match("([^\x00]+)\x00([^\x00]*)\x00")) {
            $response->addMatch($response->getMatch(1), $response->getMatch(2));
        }

        return $response->getResult();
    }

}

?>