<?php
/**
* Net_GameServerQuery_Socket
*
* Socket object for Net_GameServerQuery
* This class handles the basic socket functionality.
*
* @version        1.0
* @package        Net_GameServerQuery
*/
class Net_GameServerQuery_Socket
{
    /**
    * The resource ID of the connection
    *
    * @var          resource
    * @access       private
    */
    private $_socket;

    /**
    * True if we have a udp "connection" open.
    *
    * @var          bool
    * @access       private
    */
    private $_connected;

    /**
    * Max time in seconds to send data for
    *
    * @var          int
    * @access       private
    * @default      1
    */
    private $_timeout_stream = 1;

    /**
    * Max time in seconds to attempt connection
    *
    * @var          int
    * @access       private
    * @default      1
    */
    private $_timeout_connect = 1;

    /**
    * Hold data for the virtual buffer
    *
    * @var          string
    * @access       private
    */
    private $_buffer;

    /*
    * Connect
    *
    * @version      1.0
    * @param        string $ip IP of the server
    * @param        string $port Port of the server
    * @return       bool True if the connection is established
    * @access       public
    */
    public function connect ($ip, $port)
    {
        // "Connect"
        $fp = fsockopen('udp://' . $ip, $port, $errno, $errstr, $this->_timeout_connect);

        // Check if connection was successful
        if (false !== $fp)
        {
            // Save the connection
            $this->_socket = $fp;
            $this->_connected = true;

            // Set stream options
            socket_set_timeout($this->_socket, $this->_timeout_stream, 0);
            socket_set_blocking($this->_socket, true);

            return true;
        }
        
        return false;
    }


    /**
    * Disconnect
    *
    * @version      1.0
    * @return       bool True if the connection was closed
    * @access       public
    */
    public function disconnect ()
    {
        // Check if we're connected
        if ($this->_connected !== true) {
            return false; }

        fclose($this->_socket);
        return true;
    }


    /**
    * Send packet
    *
    * @version      1.0
    * @param        string $packet The packet to send
    * @return       bool True if the packet was sent
    * @access       public
    */
    public function send ($packet)
    {
        // Check if we're connected
        if ($this->_connected !== true) {
            return false; }

        // Send the packet
        if (!fwrite($this->_socket, $packet)) {
            return false; }
        
        return true;
    }


    /**
    * Receive packet
    *
    * This will retrieve a single packet and return it
    *
    * @version      1.0
    * @access       public
    */
    public function receive ()
    {
        // Check if we're connected
        if ($this->_connected !== true) {
            return false; }
            
        // Read the first bit and get meta information
        $data = fread($this->_socket, 1);
        $metadata = stream_get_meta_data($this->_socket);
        $bytesleft = $metadata['unread_bytes'];

        // Read the rest of the data - much faster than feof()
        $data .= fread($this->_socket, $metadata['unread_bytes']);

        return $data;
    }


    /**
    * Read $len bytes
    *
    * @access        public
    * @version        1.0
    */
    public function read ($len = null)
    {
        // If they havn't specified a length, return it all
        if ($len === null) {
            return $this-_buffer; }
    
        // If they've asked for more than we have, return false
        if ($len > strlen($this->_buffer)) {
            return false; }

        // Otherwise, get the data requested and update the buffer
        $data = substr($this->_buffer, 0, $len);
        $this->_buffer = substr($this->_buffer, $len, strlen($this->_buffer) - $len);

        return $data;
    }


    /**
    * Read 2 bytes as an Int16
    *
    * @access        public
    * @version        1.0
    */
    public function getInt16 ()
    {    
        $data = unpack("Sshort", $this->read(2));
        return $data['short'];
    }


    /**
    * Read 4 bytes as an Int32
    *
    * @access        public
    * @version        1.0
    */
    public function getInt32 ()
    {    
        $data = unpack("Lint", $this->read(4));
        return $data['int'];
    }


    /**
    * Read 4 bytes as a Float32
    *
    * @access        public
    * @version        1.0
    */
    public function getFloat32 ()
    {    
        $data = unpack("ffloat", $this->read(4));
        return $data['float'];
    }


    /**
    * Get one byte as an interger
    *
    * @access        public
    * @version        1.0
    */
    public function getByte ()
    {    
        return ord($this->read(1));
    }


    /**
    * Get one byte as a character
    *
    * @access        public
    * @version        1.0
    */
    public function getChar ()
    {    
        return $this->read(1);
    }


    /**
    * Get one byte at a time until a nullbyte, or optionally a blackslash is found
    *
    * @access        public
    * @version        1.0
    */
    public function getString ($backslash = false)
    {
        // Start our string
        $string = '';

        // Start our loop
        while (1)
        {
            // Read one byte
            $char = $this->getChar();
            
            // If the char is a nullbyte (or optionally backslash), kill our loop
            if ($char == "\x00" || ($backslash === true && $char == '\\')) {
                break; }

            // Otherwise, add it to our string
            $string .= $char;
        }

        // Return
        return $string;
    }


    /**
    * Get the entire packet
    *
    * @access        public
    * @version        1.0
    */
    public function getAll ()
    {
        return read();
    }

}
?>