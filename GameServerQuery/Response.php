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
 * Provide an interface for easy manipulation of a server response
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Response
{
    /**
     * The full server response
     *
     * @var        string
     * @access     public
     */
    private $_response;

    /**
     * The buffered server response
     *
     * @var        string
     * @access     public
     */
    private $_buffer;

    /**
     * Results from last regular expression match
     *
     * @var        array
     * @access     public
     */
    private $_match;

    /**
     * Formatted server response
     *
     * @var        array
     * @access     public
     */
    private $_result;

    /**
     * Highest player index
     *
     * @var        int
     * @access     public
     */
    private $_pindex = 0;


    /**
     * Constructor
     *
     * @param   string|array    $response   The server response
     */
    public function __construct($response)
    {
        $this->_response = $this->_buffer = $response;
    }


    /**
     * Read from the virtual buffer
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function read($length = 1)
    {
        if ($length === true) {
            $length = strlen($this->_buffer);
        }

        // Sanity check
        if ($length > strlen($this->_buffer)) {
            return false;
        }

        // Get the string
        $string = substr($this->_buffer, 0, $length);

        // Remove from buffer
        $this->_buffer = substr($this->_buffer, $length);

        return $string;
    }


    /**
     * Read the last character from the virtual buffer
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function readLast()
    {
        // Get the last char
        $string = substr($this->_buffer, -1, 1);

        // Remove from buffer
        $this->_buffer = substr($this->_buffer, 0, -1);

        return $string;
    }
    

    /**
     * Read an int32 from the buffer
     *
     * @return  int             The data read
     */
    public function readInt32()
    {
        return $this->toInt($this->read(4), 32);
    }


    /**
     * Read an int16 from the buffer
     *
     * @return  int             The data read
     */
    public function readInt16()
    {
        return $this->toInt($this->read(2), 16);
    }


    /**
     * Read an int8 from the buffer
     *
     * @return  int             The data read
     */
    public function readInt8()
    {
        return $this->toInt($this->read(1), 8);
    }


    /**
     * Read an float32 from the buffer
     *
     * @return  int             The data read
     */
    public function readFloat32()
    {
        return $this->toFloat($this->read(4));
    }


    /**
     * Read a $delim terminated string from the buffer
     *
     * If not found, read everything
     *
     * @return  int             The data read
     */
    public function readString($delim = "\x0")
    {
        $p = strpos($this->_buffer, $delim);

        if ($p === false) {
            return $this->read(true);
        }

        $string = $this->read($p);
        $this->read();

        return $string;
    }


    /**
     * Retrieve the full server response
     *
     * @return  string|array    The response
     */
    public function getResponse()
    {
        return $this->_response;
    }


    /**
     * Set the response
     *
     * @param   string|array    $response   The server response
     * @return  void
     */
    public function setResponse($response)
    {
        $this->_response = $response;
    }


    /**
     * Retrieve the full server response
     *
     * @return  string|array    The response
     */
    public function getBuffer()
    {
        return $this->_buffer;
    }


    /**
     * Check if the buffer has data
     *
     * @return  bool    TRUE if the buffer has data in it, FALSE if not
     */
    public function bufferHasData()
    {
        if (strlen($this->_buffer) === 0) {
            return false;
        }
        
        return true;
    }


    /**
     * Adds variable to results
     *
     * @param      string    $name     Variable name
     * @param      string    $value    Variable value
     */
    public function addResult($name, $value)
    {
        $this->_result[$name] = $value;
    }


    /**
     * Adds meta information to the results
     *
     * Currently prefixes key with __
     *
     * @param      string    $name     Variable name
     * @param      string    $value    Variable value
     */
    public function addMeta($name, $value)
    {
        $this->_result['__' . $name] = $value;
    }


    /**
     * Adds player variable to output
     *
     * @param   string   $name   Variable name
     * @param   string   $value  Variable value
     */
    public function addPlayer($name, $value)
    {
        // Player var is already set, so it must belong to the next player
        if (isset($this->_result[$this->_pindex][$name])) {
            ++$this->_pindex;
        }
        
        // Set player var
        $this->_result[$this->_pindex][$name] = $value;
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
        if (strlen($string) !== ($bits / 8)) {
            return false;
        }

        // Convert
        switch($bits) {

            // 8 bit unsigned
            case 8:
                $int = ord($string);
                break;

            // 16 bit unsigned
            case 16:
                $int = unpack('Sint', $string);
                $int = $int['int'];
                break;

            // 32 bit unsigned
            case 32:
                $int = unpack('Lint', $string);
                $int = $int['int'];
                break;

            // Invalid type
            default:
                $int = false;
                break;
        }

        return $int;
    }

}

?>