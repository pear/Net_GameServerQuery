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


require_once 'GameServerQuery\Protocol.php';


/**
 * Net_GameServerQuery_Protocol_HalfLife
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_HalfLife extends Net_GameServerQuery_Protocol
{

    /**
     * Conversion class
     *
     * @access     private
     * @var        resource
     */
    private $_convert;


    /**
     * Constructor
     *
     * @access     public
     */ 
    public function __construct()
    {
        // Initialize conversion class
        $this->_to = new Net_GameServerQuery_Convert;

        // Define packets
        $this->_packets = array(
            'details'    => "\xff\xff\xff\xffdetails",
            'infostring' => "\xff\xff\xff\xffinfostring",
            'ping'       => "\xff\xff\xff\xffping",
            'players'    => "\xff\xff\xff\xffplayers",
            'rules'      => "\xff\xff\xff\xffrules"
        );
        
        // Define packet mapping array
        $this->_map = array(
            'ping'    => 'ping',
            'players' => 'players',
            'rules'   => 'rules',
            'status'  => 'infostring'
        );
    }
 

    /**
     * Details packet
     *
     * @access    private
     * @return    array      Array containing formatted server response
     */
    private function _details()
    {
        // Header
        if (!$this->_match("\xff\xff\xff\xff\x6d")) {
            return false;
        }
        
        // Body regular expression
        $body = "([^\\x00+)\\x00([^\\x00+)\\x00([^\\x00+)\\x00([^\\x00+)\\x00"
              . "([^\\x00+)\\x00(.)(.)(.)(.)(.)(.)(.)";

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
                        $this->_result[$i+1] = $this->_convert->toInt($this->_result[$i+1]);
                    default:
                        $this->_addVar($vars[$i], $this->_result[$i+1]);
                        break;
                }
            }
            
        }
        

    }
    

    /**
     * Infostring packet
     *
     * @access     private
     * @return     array     Array containing formatted server response
     */
    private function _infostring()
    {
        // Header
        if (!$this->_match("\xff\xff\xff\xffinfostringresponse\\x00")) {
            return false;
        }

        // Variable / value pairs
        while ($this->_match("([^\\x00]*)\\x00([^\\x00]*)\\x00")) {
            $this->_addVar($this->_result[1], $this->_result[2]);
        }

        return $this->_output;
    }
    

    /**
     * Ping packet
     *
     * @access     private
     * @return     array     Array containing formatted server response
     */
    private function _ping()
    {
        if ($this->_match("\xff\xff\xff\xff\x6a")) {
            return $this->_output;
        }
        else {
            return false;
        }
    }


    /**
     * Players packet
     *
     * @access     private
     * @return     array     Array containing formatted server response
     */
    private function _players()
    {
        // Header
        if ($this->_match("\xff\xff\xff\xffx6d(.)")) {
            $this->_addVar('playercount', ord($this->_result[1]));
        }
        else {
            return false;
        }

        // Players
        while ($this->_match("(.)([^\\x00]+)\\x00(.{4})(.{4})")) {
            $this->_addVar('playerid',    $this->_convert->toInt($this->_result[1]));
            $this->_addVar('playername',  $this->_result[2]);
            $this->_addVar('playerscore', $this->_convert->toInt($this->_result[3], 32));
            $this->_addVar('playertime',  $this->_convert->toFloat($this->_result[4]));
        }

        return $this->_output;
        
    }


    /**
     * Rules packet
     *
     * @access     private
     * @return     array     Array containing formatted server response
     */
    private function _rules()
    {
        // Remove the header of the possible second packet
        str_replace("\xfe\xff\xff\xff", '', $this->_response);
        
        // Get header, rulecount
        if ($this->_match("\xff\xff\xff\xff\x45(.{2})")) {
            $this->_addVar('rulecount', $this->_convert->toInt($this->_result[1], 16));
        }
        else {
            return false;
        }

        // Variable / value pairs
        while ($this->_match("([^\\x00]*)\\x00([^\\x00]*)\\x00")) {
            $this->_addVar($this->_result[1], $this->_result[2]);
        }

        return $this->_output;
    }
     
}
?>
