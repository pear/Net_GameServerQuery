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
 * Quake3 Protocol
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery

 */
class Net_GameServerQuery_Protocol_Quake3 extends Net_GameServerQuery_Protocol
{
    /*
     * Rules packet
     */
    protected function rules(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read(14) !== 'statusResponse') {
            return false;
        }

        if ($buffer->read(2) !== "\x0a\\") {
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


    /*
     * Players packet
     */
    protected function players(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read(14) !== 'statusResponse') {
            return false;
        }

        if ($buffer->read(2) !== "\x0a\\") {
            return false;
        }

        // Ignore all the rules information
        $buffer->readString("\x0a");

        while ($buffer->getLength()) {
            $result->addPlayer('frags', $buffer->readString("\x20"));
            $result->addPlayer('ping', $buffer->readString("\x20"));

            if ($buffer->read() !== '"') {
                return false;
            }

            $result->addPlayer('nick', $buffer->readString('"'));

            if ($buffer->read() !== "\x0a") {
                return false;
            }
        }

        return $result->fetch();
    }


    /*
     * Status packet
     */
    protected function info(&$buffer, &$result)
    {
        if ($buffer->readInt32() !== -1) {
            return false;
        }

        if ($buffer->read(12) !== 'infoResponse') {
            return false;
        }

        if ($buffer->read(2) !== "\x0a\\") {
            return false;
        }

        while ($buffer->getLength()) {
            $result->add($buffer->readString('\\'), $buffer->readString('\\'));
        }

        return $result->fetch();
    }
}

?>
