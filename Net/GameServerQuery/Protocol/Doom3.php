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
 * Doom3 Protocol
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */
class Net_GameServerQuery_Protocol_Doom3 extends Net_GameServerQuery_Protocol
{
    /*
     * Players
     */
    protected function players(&$buffer, &$result)
    {
        // Header
        if ($buffer->readInt16() !== 65535) {
            return false;
        }
        if ($buffer->readString() !== 'infoResponse') {
            return false;
        }
        if ($buffer->readInt32() !== 0) {
            return false;
        }

        // Unknown
        $buffer->read(4);

        // Skip rules
        while ($buffer->readString() !== '') {      
            $buffer->readString();
        }
        
        if ($buffer->read() !== "\x00") {
            return false;
        }
        
        // Players
        while (($id = $buffer->readInt8()) !== 32) {
            $result->addPlayer('id',   $id);
            $result->addPlayer('ping', $buffer->readInt16());
            $result->addPlayer('rate', $buffer->readInt16());
            $buffer->read(2);
            $result->addPlayer('name', $buffer->readString());
        }

        return $result->fetch();
    }

    
    /*
     * Status
     * Rules
     */
    protected function getinfo(&$buffer, &$result)
    {
        // Header
        if ($buffer->readInt16() !== 65535) {
            return false;
        }
        if ($buffer->readString() !== 'infoResponse') {
            return false;
        }
        if ($buffer->readInt32() !== 0) {
            return false;
        }

        // Unknown
        $buffer->read(4);

        // Var / value pairs, delimited by an empty pair
        while (($varname = $buffer->readString()) !== '') {      
            $result->add($varname, $buffer->readString());
        }
        
        if ($buffer->read() !== "\x00") {
            return false;
        }
        
        // Get player count
        $count = 0;
        while ($buffer->readInt8() !== 32) {
            ++$count;
            $buffer->readInt16();
            $buffer->readInt16();
            $buffer->read(2);
            $buffer->readString();
        }
        $result->add('si_numplayers', $count);

        return $result->fetch();
    }
    
}

?>
