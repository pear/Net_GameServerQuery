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
        // Header
        if (!$this->_match("\xff\xffinfoResponse")) {
            return false;
        }

        // Probably a (protocol) version number
        if (!$this->_match("(.{8})\\x00")) {
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
        while ($this->_match("(.)(..)(.)(.)(..)([^\\x00]+)\\x00")) {
            
            $this->_addVar('playerid', $this->_convert->toInt($this->_result[1], 8));
            $this->_addVar('playerping', $this->_convert->toInt($this->_result[2], 16));
            
            // These are teamflags probably, either \x80\x3e or \x50\xc3,
            // always \x80\x3e in deatmatch
            $this->_addVar('tf0', $this->_convert->toInt($this->_result[3], 8));
            $this->_addVar('tf1', $this->_convert->toInt($this->_result[4], 8));
            
            // Result[5] holds two bits, who always seem to be \x00\x00
            
            $this->_addVar('playername', $this->_result[6]);
        }

        return $this->_output;
    }
}

?>