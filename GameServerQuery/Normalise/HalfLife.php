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
 * HalfLife Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Protocol_halflife extends Net_GameServerQuery_Protocol
{

    /**
     * Hold an instance of the conversion class
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
        $this->_convert = new Net_GameServerQuery_Convert;

        // Define packets
        $this->_packets = array(
            'details'    => "\xFF\xFF\xFF\xFFdetails",
            'infostring' => "\xFF\xFF\xFF\xFFinfostring",
            'ping'       => "\xFF\xFF\xFF\xFFping",
            'players'    => "\xFF\xFF\xFF\xFFplayers",
            'rules'      => "\xFF\xFF\xFF\xFFrules"
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
        if (!$this->_match("\xFF\xFF\xFF\xFF\x6d")) {
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
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _infostring()
    {
        // Header
        if (!$this->_match("\xFF\xFF\xFF\xFFinfostringresponse\\x00")) {
            return false;
        }

        // Variable / value pairs
        while ($this->_match("\\\\([^\\\\]*)\\\\([^\\x00\\\\]*)")) {
            $this->_addVar($this->_result[1], $this->_result[2]);
        }

        // Terminating character
        if (!$this->_match("\\x00")) {
            return false;
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
        if ($this->_match("\xFF\xFF\xFF\xFF\x6a")) {
            return $this->_output;
        } else {
            return false;
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
            $this->_addVar('playercount', $this->_convert->toInt($this->_result[1]));
        } else {
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
     * @access     protected
     * @return     array     Array containing formatted server response
     */
    protected function _rules()
    {
        // Remove the header of the possible second packet
        $this->_response = preg_replace("/\xfe\xFF\xFF\xFF.{5}/", '', $this->_response);

        // Get header, rulecount
        if ($this->_match("\xFF\xFF\xFF\xFF\x45(.{2})")) {
            $this->_addVar('rulecount', $this->_convert->toInt($this->_result[1], 16));
        } else {
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