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
     * Constructor
     *
     * @access     public
     */ 
    public function __construct()
    {
        parent::__construct();

        // Define packets
        $this->_packets = array(
            'getinfo' => "\xFF\xFFgetInfo\x00\x00\x00\x00\x00"
        );
        
        // Define packet mapping array
        $this->_map = array(
            'status'  => 'getinfo'
        );

    }

    /**
     * Details packet
     *
     * @access    private
     * @return    array      Array containing formatted server response
     */
    protected function _getinfo()
    {    
        hexdump($this->_response);
        // Header
        if (!$this->_match("\xff\xffinfoResponse")) {
            return false;
        }

        // Two integers probably, look at this later
        if ($this->_match("(.{4})(.{4})\\x00")) {
            print_r(unpack("Va", $this->_result[2]));
        }
        else {
            return false;
        }

        // Variable / value pairs
        while ($this->_match("([^\\x00]+)\\x00([^\\x00]*)\\x00")) {
            $this->_addVar($this->_result[1], $this->_result[2]);
        }

        // End marker for variables?
        if (!$this->_match("\\x00\\x00")) {
            return false;
        }

        // Players (ping and score in here somehwere)
        while ($this->_match("(.)(.{6})([^\\x00]+)\\x00")) {
            $this->_addVar('playerid', $this->_convert->toInt($this->_result[1], 8));
            $this->_addVar('playername', $this->_result[3]));
        }

        return $this->_output;
    }
}

?>