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
 * @author         Aidan Lister <aidan@php.net>
 * @version        $Revision$
 */
interface Net_GameServerQuery_Protocol_Interface
{
    public function process($packetname, $response);
}


/**
 * Abstract class which all protocol classes must inherit
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Tom Buskens <ortega@php.net>
 * @author         Aidan Lister <aidan@php.net>
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
     * Highest player index
     *
     * @access     protected
     * @var        int
     */
    protected $_player_index;


    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialise variables
        $this->_player_index = 0;
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

        // Process packet
        $function = '_' . $packetname;
        return $this->{$function}();
    }


    /**
     * Match response to regular expression
     *
     * @access     protected
     * @param      string    $expr       Regular expression
     * @return     bool      True if expression was matched, false otherwise
     */
    protected function _match($expr)
    {
        // Clear any previous matches
        $this->_result = array();

        // We need to escape nullbytes, and single slashes
        $expr = addslashes($expr);

        // Format regular expression
        $expr = sprintf('#^%s#s', $expr);

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
     * Remove and return a prefix from the response string
     *
     * @access  protected
     * @param   int      $length  Length of the prefix
     * @return  string   The prefix
     */
    protected function _getPrefix($length = 1)
    {
        // Get prefix
        $result          = substr($this->_response, 0, $length);

        // Remove prefix from response string
        $remainder       = strlen($this->_response) - $length;
        $this->_response = substr($this->_response, $length, $remainder);

        return $result;
    }


    /**
     * Adds variable to output
     *
     * @access     protected
     * @param      string    $name     Variable name
     * @param      string    $value    Variable value
     */
    protected function _add($name, $value)
    {
        $this->_output[$name] = $value;
    }


    /**
     * Adds player variable to output
     *
     * @access  protected
     * @param   string   $name   Variable name
     * @param   string   $value  Variable value
     */
    protected function _addPlayer($name, $value)
    {
        // Player var is already set, so it must belong to the next player
        if (isset($this->_output[$this->_player_index][$name])) {
            ++$this->_player_index;
        }
        
        // Set player var
        $this->_output[$this->_player_index][$name] = $value;
    }


    /**
     * Conversion to float
     *
     * @access     public
     * @param      string    $string   String to convert
     * @return     float     32 bit float
     */
    public function toFloat($string)
    {
        // Check length
        if (strlen($string) !== 4) {
            return false;
        }

        // Convert
        $float = unpack('ffloat', $string);
        return $float['float'];
    }


    /**
     * Conversion to integer
     *
     * @access     public
     * @param      string    $string   String to convert
     * @param      int       $bits     Number of bits
     * @return     int       Integer according to type
     */
    public function toInt($string, $bits = 8)
    {
        // Check length
        if (strlen($string) !== ($bits/8)) {
            return false;
        }

        // Convert
        switch($bits) {

            // 8 bit, unsigned
            case 8:
                $int = ord($string);
                break;

            // 16 bit, unsigned
            case 16:
                $int = unpack('Sint', $string);
                $int = $int['int'];
                break;

            // 32 bit, unsigned
            case 32:
                $int = unpack('Lint', $string);
                $int = $int['int'];
                break;

            // invalid type
            default:
                $int = false;
                break;
        }

        return $int;
    }

}

?>