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
 * HalfLife2 Protocol
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Protocol_HalfLife2 extends Net_GameServerQuery_Protocol
{
    /**
     * Status
     */
    protected function details(&$response, &$result)
    {
        if ($response->readInt32() !== -1) {
            return false;
        }

        if ($response->read() !== 'I') {
            return false;
        }
        
        $result->add('protocol',    $response->readInt8());
        $result->add('hostname',    $response->readString());
        $result->add('map',         $response->readString());
        $result->add('gamedir',     $response->readString());
        $result->add('gamedescrip', $response->readString());
        $result->add('steamappid',  $response->readInt32());
        $result->add('numplayers',  $response->readInt8());
        $result->add('maxplayers',  $response->readInt8());
        $result->add('botcount',    $response->readInt8());
        $result->add('server',      $response->readInt8());
        $result->add('os',          $response->readInt8());
        $result->add('password',    $response->readInt8());
        $result->add('secure',      $response->readInt8());
        
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