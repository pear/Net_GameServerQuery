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


require_once NET_GAMESERVERQUERY_BASE . 'Protocol/Unreal2.php';


/**
 * Unreal 2 XMP protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_Unreal2XMP extends Net_GameServerQuery_Protocol_Unreal2
{
    /**
     * Players packet
     */
    protected function players(&$buffer, &$result)
    {
        // Header
        $buffer->read(4);

        // Packet type
        if ($buffer->read() !== "\x02") {
            return false;
        }

        while ($buffer->getLength() > 0) {
            $result->addPlayer('number',  $buffer->readInt32()); // 0
            $result->addPlayer('id',      $buffer->readInt32()); // 0
            $buffer->readAhead(4);
            $result->addPlayer('name',    $this->readU2xmpString(&$buffer));
            $result->addPlayer('ping',    $buffer->readInt32());
            $result->addPlayer('score',   $buffer->readInt32());
            $buffer->read(4);   // stats, 0
            
            $count = $buffer->readInt8();
            for ($i = 0; $i !== $count; $i++) {
                $result->addPlayer(
                    $buffer->readPascalString(1),
                    $this->readU2xmpString(&$buffer)
                );
            }
        }
        return $result->fetch();
    }

    /**
     * Unreal 2 XMP color coded strings
     */
    private function readU2xmpString(&$buffer)
    {
        // Check for color coding marker
        if (substr($buffer->readAhead(5), 1) === "\x5e\x00\x23\x00") {
            $length = ($buffer->readInt8() - 128) * 2;
            return $buffer->read($length);
        }
        
        // No marker, normal string
        else {
            return $buffer->readPascalString(1);
        }
        
    }
}
?>
