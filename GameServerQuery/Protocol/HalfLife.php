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
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_HalfLife extends Net_GameServerQuery_Protocol
{
    protected function details(&$response)
    {
        // Header
        if (!$response->_match("\xFF\xFF\xFF\xFF\x6d")) {
            return false;
        }

        // Body regular expression
        $pattern = "(([^\x00]+)\x00([^\x00]+)\x00([^\x00]+)\x00([^\x00]+)\x00"
              . "([^\x00]+)\x00(.)(.)(.)(.)(.)(.)(.)";

        // Body variable names
        $keys = array('serverip', 'servername', 'mapname', 'gamedir',
                      'gamename', 'playercount', 'playermax',
                      'protocolversion', 'servertype', 'serveros',
                      'serverpassword', 'gamemod'
        );

        // Match body
        if ($response->match($pattern)) {

            // Process and save variables
            for ($i = 0, $ii = count($vars); $i < $ii; $i++) {
                switch ($i) {
                    case  5:
                    case  6:
                    case  7:
                    case 10:
                    case 11:
                        $pattern->_result[$i+1] = $this->toInt($this->_result[$i+1]);
                    default:
                        $this->_add($vars[$i], $this->_result[$i+1]);
                        break;
                }
            }

        } else {
            return false;
        }

        return $response->getResult();
    }

    protected function infostring(&$response)
    {
        // Header
        if (!$this->_match("\xFF\xFF\xFF\xFFinfostringresponse\x00")) {
            throw new Exception('Parsing error');
        }

        // Variable / value pairs
        while ($this->_match("\\([^\\]+)\\([^\\\x00]*)")) {
            $this->_add($this->_result[1], $this->_result[2]);
        }

        // Terminating character
        if (!$this->_match("\x00")) {
            throw new Exception('Parsing error');
        }

        return $this->_output;
    }

    protected function ping(&$response)
    {
        if ($this->_match("\xFF\xFF\xFF\xFF\n")) {
            return $this->_output;
        }

        throw new Exception('Parsing error');
    }

    protected function players(&$response)
    {
        // Header
        if ($this->_match("\xFF\xFF\xFF\xFF\x44(.)")) {
            //$this->_add('playercount', $this->toInt($this->_result[1]));
        } else {
            throw new Exception('Parsing error');
        }

        // Players
        while ($this->_match("(.)([^\x00]+)\x00(.{4})(.{4})")) {
            $this->_addPlayer('id',    $this->toInt($this->_result[1]));
            $this->_addPlayer('name',  $this->_result[2]);
            $this->_addPlayer('score', $this->toInt($this->_result[3], 32));
            $this->_addPlayer('time',  $this->toFloat($this->_result[4]));
        }

        return $this->_output;
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
            $response->addMatch('__rulecount', $response->toInt($response->getMatch(1), 16));
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