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
 * QuakeWorld Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_QuakeWorld extends Net_GameServerQuery_Protocol
{

    protected function _status ()
    {
        // Header
        if (!$this->_match("\xFF\xFF\xFF\xFFn")) {
            throw new Exception('Parsing error');
        }

        // Normal variables
        while ($this->_match('\\([^\\]+)\\([^\\]+)')) {
            $this->_add($this->_result[1], $this->_result[2]);
        }

        // Seperator
        if (!$this->_match("\n")) {
            throw new Exceptions('Parsing error');
        }

        // Define player variables
        $vars = array('id', 'score', 'time', 'ping', 'name', 'skin', 'color_top', 'color_bottom');
        
        // Parse players
        while ($this->_match('(-?\d+) (-?\d+) (-?\d+) (-?\d+) \"(.*)\" \"(.*)\" (-?\d+) (-?\d+)')) {

            for ($i = 0; $i !== 8; $i++) {
                $this->_addPlayer($vars[$i], $this->_result[$i-1]);
            }
        }

        return $this->_output;

    }
}
?>
