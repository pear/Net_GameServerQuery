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
 * Doom 3 Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_Doom3 extends Net_GameServerQuery_Protocol
{
    /**
     * GetInfo packet
     */
    protected function status(&$buffer, &$result)
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

        // ?
        $buffer->read(4);

        // Var / value pairs, delimited by an empty pair
        while (true) {
            
            $varname = $buffer->readString();
            if (empty($varname)) {
                break;
            }
            
            $result->add(
                $varname,
                $buffer->readString()
            );
        }
        
        if ($buffer->read() !== "\x00") {
            return false;
        }

        // Players, delimited by player id 32
        while (($id = $buffer->readInt8()) !== 32) {
            $result->addPlayer('id',   $id);
            $result->addPlayer('ping', $buffer->readInt16());
            $result->addPlayer('rate', $buffer->readInt16());
            $buffer->read(2);
            $result->addPlayer('name', $buffer->readString());
            
        }

        return $result->fetch();
    }
}

?>
