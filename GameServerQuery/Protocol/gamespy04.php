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
 * GameSpy04 Protocol
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 * @todo           implement all parsing functions
 */
class Net_GameServerQuery_Protocol_gamespy04 extends Net_GameServerQuery_Protocol
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
        $this->_convert = new Net_GameServerQuery_Convert;

        // Define packets
        $this->_packets = array(
            'rules'   => "\xfe\xfd\x00NGSQ\xff\x00\x00",
            'players' => "\xfe\xfd\x00NGSQ\x00\xff\xff",
            'team'    => "\xfe\xfd\x00NGSQ\x00\x00\xff"
        );
        
        // Define packet mapping array
        $this->_map = array(
            'ping'    => 'team',
            'players' => 'players',
            'rules'   => 'rules',
        );
    }


    /**
     * Rules packet
     *
     * @access    private
     * @return    array      Array containing formatted server response
     */
    protected function _rules()
    {
        // Header
        if (!$this->_match("\\x00NGSQ")) {
            return false;
        }

        // Variable / value pairs
        while ($this->_match("([^\\x00]+)\\x00([^\\x00]*)\\x00")) {
            $this->_addVar($this->_result[1], $this->_result[2]);
        }

        return $this->_output;
        
    }


    /**
     * Player packet
     *
     * @access     private
     * @return     array     Array containing formatted server response
     */
    private function _players()
    {
        // Header
        if (!$this->_match("\\x00NGSQ")) {
            return false;
        }
    }

}


/**
 * Normaliser class
 */
class Net_GameServerQuery_Protocol_Normaliser_gamespy04
{
    public function process($packetname, $data)
    {
        return $data;
    }
}
?>