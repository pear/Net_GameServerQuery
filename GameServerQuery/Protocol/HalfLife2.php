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
    protected function details(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read() !== 'I') {
            return false;
        }
        
        $result->add('protocol',    $buffer->readInt8());
        $result->add('hostname',    $buffer->readString());
        $result->add('map',         $buffer->readString());
        $result->add('gamedir',     $buffer->readString());
        $result->add('gamedescrip', $buffer->readString());
        $result->add('steamappid',  $buffer->readInt32());
        $result->add('numplayers',  $buffer->readInt8());
        $result->add('maxplayers',  $buffer->readInt8());
        $result->add('botcount',    $buffer->readInt8());
        $result->add('server',      $buffer->readInt8());
        $result->add('os',          $buffer->readInt8());
        $result->add('password',    $buffer->readInt8());
        $result->add('secure',      $buffer->readInt8());
        
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

        if ($buffer->read() !== 'D') {
            return false;
        }

        $result->addMeta('count', $buffer->readInt8());

        while (!$buffer->is_empty()) {
            $result->addPlayer('id',      $buffer->readInt8());
            $result->addPlayer('name',    $buffer->readString());
            $result->addPlayer('score',   $buffer->readInt32());
            $result->addPlayer('time',    $buffer->readFloat32());
        }

        return $result->fetch();
    }


    /**
     * Rules
     */
    protected function rules(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read() !== 'E') {
            return false;
        }

        $result->addMeta('count', $buffer->readInt16());

        while (!$buffer->is_empty()) {
            $result->add($buffer->readString(), $buffer->readString());
        }

        return $result->fetch();
    }

}

?>