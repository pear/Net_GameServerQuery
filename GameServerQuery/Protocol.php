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


require_once NET_GAMESERVERQUERY_BASE . 'Response.php';


/**
 * Abstract class which all protocol classes must inherit
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
abstract class Net_GameServerQuery_Protocol
{
    /**
     * Parse server response according to packet type
     *
     * @access     public
     * @param      array     $packet   Array containing the packet and its type
     * @return     array     Array containing formatted server response
     */
    public function parse($packetname, $response)
    {
        // Response class
        $response = new Net_GameServerQuery_Response($response);

        // Parse packet
        $result = call_user_func(array($this, $packetname), $response);

        // Return result
        if ($result !== false) {
            return $result;
        }

        throw new Exception('Parsing Error');
    }

}

?>