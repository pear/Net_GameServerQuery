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
 * Define the interface for all protocol classes
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
interface Net_GameServerQuery_Protocol_Interface
{
    public function process($packetname, $response);
    public function getpacket($packetname);
}


/**
 * Abstract class which all protocol classes must inherit
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
abstract class Net_GameServerQuery_Protocol implements Net_GameServerQuery_Protocol_Interface
{
    /**
     * Server response
     *
     * @var        string
     * @access     protected
     */
    protected $_response;
    
    
    /**
     * Results from last regular expression match
     *
     * @var        array
     * @access     protected
     */
    protected $_result;
    
    
    /**
     * Formatted server response
     *
     * @var        array
     * @access     protected
     */
    protected $_output;    
 
    /**
     * Packets to send to server
     *
     * @var        array
     * @access     protected
     */
    protected $_packets;

    /**
     * Used to map abstract packets to those used by the specific protocol
     *
     * @var        array
     * @access     protected
     */
    protected $_map;
    
    /**
     * Hold an instance of the conversion class
     *
     * @access     private
     * @var        resource
     */
    protected $_convert;


    public function __construct()
    {
        // Initialize conversion class
        $this->_convert = new Net_GameServerQuery_Convert;
    }
    
    /**
     * Process server response according to packet type
     *
     * @access     public
     * @param      array     $packet   Array containing the packet and its type
     * @return     array     Array containing formatted server response
     */
    public function process($packetname, $response)
    {
        // Clear previous output
        $this->_output = array();
        
        // Get packet data
        $this->_response = $response;
        
        // Check if packet type exists, process packet
        if (isset($this->_packets[$packetname])) {
            $function = '_' . $packetname;
            return $this->{$function}();
        } else {
            return false;
        }
    }


    /**
     * Translate abstract packet to one or more actual packets
     *
     * @access     public
     * @param      string    $packet   Abstract packet
     * @return     array     Packet and packet name
     */
    public function getpacket($packet)
    {
        // Map packets to those used by the protocol
        if (isset($this->_map[$packet])) {
            
            $name = $this->_map[$packet];
            $result['packetname'] = $name;
            $result['packet']     = $this->_packets[$name];
            return $result;

        } else {
            return false;
        }
    }


    /**
     * Match response to regular expression
     *
     * @access     private
     * @param      string    $expr       Regular expression
     * @return     bool      True if expression was matched, false otherwise
     */
    protected function _match($expr)
    {
        // Clear any previous matches
        $this->_result = array();
        
        // Format regular expression
        $expr = sprintf("/^%s/s", $expr);

        // Match pattern
        if (preg_match($expr, $this->_response, $this->_result) == false) {
            $status = false;
        } else {
            // Remove pattern from response
            if (!empty($this->_result[0])) {
                $this->_response = substr($this->_response, strlen($this->_result[0]));
            }

            $status = true;
        }

        return $status;
    }
    

    /**
     * Adds variable to output
     *
     * @access     private
     * @param      string    $name     Variable name
     * @param      string    $value    Variable value
     */
    protected function _addVar($name, $value)
    {
        // Existing variable
        if (isset($this->_output[$name])) {
            
            // Variable has one value, put it into an array
            if (!is_array($this->_output[$name])) {
                $this->_output[$name] = array($this->_output[$name]);
            }

            // Add current match to array
            array_push($this->_output[$name], $value);
            
        } else {
            // New variable
            $this->_output[$name] = $value;
        }
    }
}

?>