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
     * The original data
     *
     * @var        string
     * @access     public
     */
    private $_length;
    
    
    /**
     * Position of pointer
     *
     * @var        string
     * @access     public
     */
    private $_index = 0;


    /**
     * Constructor
     *
     * @param   string|array    $response   The data
     */
    public function __construct($data)
    {
        $this->_data   = $data;
        $this->_length = strlen($data);
    }


    /**
     * Return all the data
     *
     * @return  string|array    The data
     */
    public function getData()
    {
        return $this->_data;
    }
    

    /**
     * Return data currently in the buffer
     *
     * @return  string|array    The data currently in the buffer
     */
    public function getBuffer()
    {
        return substr($this->_data, $this->_index);
    }


    /**
     * Returns the number of bytes in the buffer
     *
     * @return  int  Length of the buffer
     */
    public function getLength()
    {
        return ($this->_length - $this->_index);
    }


    /**
     * Read from the buffer
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function read($length = 1)
    {
        $string = substr($this->_data, $this->_index, $length);
        $this->_index += $length;
        
        return $string;
    }


    /**
     * Read the last character from the buffer
     *
     * Unlike the other read functions, this function actually removes
     * the character from the buffer.
     *
     * @return  string          The data read
     */
    public function readLast()
    {
        $len            = strlen($this->_data);
        $string         = $this->_data{strlen($this->_data) - 1};
        $this->_data    = substr($this->_data, 0, $len - 1);
        $this->_length -= 1;
        
        return $string;
    }
    

    /**
     * Read from the buffer
     *
     * @param   int             $length     Length of data to read
     * @return  string          The data read
     */
    public function readAhead($length = 1)
    {
        $string = substr($this->_data, $this->_index, $length);
        
        return $string;
    }
    
    
    /**
     * Skip forward in the buffer
     *
     * @param   int             $length     Length of data to skip
     * @return  void
     */
    public function skip($length = 1)
    {
        $this->_index += $length;
    }
    
    
    /**
     * Read from buffer until delimiter is reached
     *
     * If not found, return everything
     *
     * @param   string          $delim      Read until this character is reached
     * @return  string          The data read
     */
    public function readString($delim = "\x00")
    {
        // Get position of delimiter
        $len = strpos($this->_data, $delim, $this->_index);
        
        // If it is not found then return whole buffer
        if ($len === false) {
            return $this->read(strlen($this->_data) - $this->_index);
        }

        // Read the string and remove the delimiter
        $string = $this->read($len - $this->_index);
        ++$this->_index;
       
        return $string;
    }


    /**
     * Reads a pascal string from the buffer
     *
     * @return  string  The data read
     */
    public function readPascalString($offset = 0)
    {
        // Get length of the string
        $len = $this->readInt8();
        $offset = max($len - $offset, 0);
        return substr($this->read($len), 0, $offset);
    }
        
    
    /**
     * Read from buffer until any of the delimiters is reached
     *
     * If not found, return everything
     *
     * @param   array           $delims      Read until these characters are reached
     * @return  string          The data read
     */
    public function readStringMulti($delims, &$delimfound = null)
    {
        // Get position of delimiters
        $pos = array();
        foreach ($delims as $delim) {
            if ($p = strpos($this->_data, $delim, $this->_index)) {
                $pos[] = $p;
            }
        }
        
        // If none are found then return whole buffer
        if (empty($pos)) {
            return $this->read(strlen($this->_data) - $this->_index);
        }

        // Read the string and remove the delimiter
        sort($pos);
        $string = $this->read($pos[0] - $this->_index);
        $delimfound = $this->read();
       
        return $string;
    }
    

    /**
     * Read an int32 from the buffer
     *
     * @return  int             The data read
     */
    public function readInt32()
    {     
        $int = unpack('Lint', $this->read(4));
        return $int['int'];
    }


    /**
     * Read an int16 from the buffer
     *
     * @return  int             The data read
     */
    public function readInt16()
    {
        $int = unpack('Sint', $this->read(2));
        return $int['int'];
    }


    /**
     * Read an int8 from the buffer
     *
     * @return  int             The data read
     */
    public function readInt8()
    {
        return ord($this->read(1));
    }


    /**
     * Read an float32 from the buffer
     *
     * @return  int             The data read
     */
    public function readFloat32()
    {
        $float = unpack('ffloat', $this->read(4));
        return $float['float'];
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
