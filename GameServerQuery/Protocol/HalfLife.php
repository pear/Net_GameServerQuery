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

    /**
     * Details packet
     *
     * @access    private
     * @return    array      Array containing formatted server response
     */
    private function _details()
    {
        // Header
        if (!$this->_match("\xFF\xFF\xFF\xFF\x6d")) {
            throw new Exception('Parsing error');
        }

        // Body regular expression
        $body = "(([^\x00]+)\x00([^\x00]+)\x00([^\x00]+)\x00([^\x00]+)\x00"
              . "([^\x00]+)\x00(.)(.)(.)(.)(.)(.)(.)";

        // Body variable names
        $vars = array('serverip', 'servername', 'mapname', 'gamedir',
                      'gamename', 'playercount', 'playermax',
                      'protocolversion', 'servertype', 'serveros',
                      'serverpassword', 'gamemod'
        );

        // Match body
        if ($this->_match($body)) {

            // Process and save variables
            for ($i = 0, $x = count($vars); $i != $x; $i++) {
                switch ($i) {
                    case  5:
                    case  6:
                    case  7:
                    case 10:
                    case 11:
                        $this->_result[$i+1] = $this->toInt($this->_result[$i+1]);
                    default:
                        $this->_add($vars[$i], $this->_result[$i+1]);
                        break;
                }
            }

        }
        else {
            throw new Exception('Parsing error');
        }

        return $this->_output;
    }


    /**
     * Infostring packet
     *
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _infostring()
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


    /**
     * Ping packet
     *
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _ping()
    {
        if ($this->_match("\xFF\xFF\xFF\xFF\n")) {
            return $this->_output;
        } else {
            throw new Exception('Parsing error');
        }
    }


    /**
     * Players packet
     *
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _players()
    {
        // Header
        if ($this->_match("\xFF\xFF\xFF\xFF\x44(.)")) {
            $this->_add('playercount', $this->toInt($this->_result[1]));
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


    /**
     * Rules packet
     *
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _rules()
    {
        // Remove the header of the possible second packet
        $this->_response = preg_replace("/\xFE\xFF\xFF\xFF.{5}/", '', $this->_response);

        // Get header, rulecount
        if ($this->_match("\xFF\xFF\xFF\xFF\x45(.{2})")) {
            $this->_add('rulecount', $this->toInt($this->_result[1], 16));
        } else {
            throw new Exception('Parsing error');
        }

        // Variable / value pairs
        while ($this->_match("([^\x00]+)\x00([^\x00]*)\x00")) {
            $this->_add($this->_result[1], $this->_result[2]);
        }

        return $this->_output;
    }

}

?>
