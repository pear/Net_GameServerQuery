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
class Net_GameServerQuery_Process_Buffer
{
    /**
     * The original data
     *
     * @var        string
     * @access     public
     */
    private $_data;

    /**
     * The buffered data
     *
     * @var        string
     * @access     public
     */
    private $_buffer;


    /**
     * Constructor
     *
     * @param   string|array    $response   The data
     */
    public function __construct($data)
    {
        $this->_data = $this->_buffer = $data;
    }


    /**
     * Retrieve the full data
     *
     * @return  string|array    The response
     */
    public function getData()
    {
        return $this->_data;
    }


    /**
     * Set the full data
     *
     * @param  string|array    The data
     */
    public function setData($data)
    {
        $this->_data = $data;
    }
    

    /**
     * Retrieve all information from the buffer
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
    public function is_empty()
    {
        if (strlen($this->_buffer) === 0) {
            return true;
        }
        
        return false;
    }


    /**
     * Read from the buffer
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function read($length = 1, $lookahead = false)
    {
        // Return whole buffer
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
        if ($lookahead === false) {
            $this->_buffer = substr($this->_buffer, $length);
        }

        return $string;
    }


    /**
     * Read from buffer until delimiter is reached
     *
     * If not found, return everything
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
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
     * Read the last character from the buffer
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