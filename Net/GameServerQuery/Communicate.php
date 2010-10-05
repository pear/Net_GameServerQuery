<?php
/**
 * PEAR :: Net_GameServerQuery
 *
 * PHP version 4
 *
 * Copyright (c) 1997-2004 The PHP Group
 *
 * This source file is subject to version 3.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/3_0.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */


/**
 * Handles all communication between client and game server
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */
class Net_GameServerQuery_Communicate
{
    /**
     * An array linking the connection resources to server keys
     *
     * @var         array
     */
    private $_serverkeys = array();
     
    
    /**
     * Perform a batch query
     *
     * This runs open, write, listen and close sequentially
     *
     * @param       array       $servers        Server data (n=>addr,port,packet)
     * @param       int         $timeout        Maximum wait time (milliseconds)
     * @return      array       An array of results
     */
    public function query($servers, $timeout)
    {
        // Open and write
        $sockets = array();
        foreach ($servers as $key => $server) {     
            // Attempt to connect to the server
            $socket = $this->_open($server['addr'], $server['port']);
  
            if ($socket === false) {
                continue;   
            }
            
            // Save the connection for this server
            $sockets[$key] = $socket;
            
            // Associate the connection id with the server id
            $this->_serverkeys[(int) $socket] = $key;
            
            // Send the packet
            $this->_write($socket, $server['packet']);
        }

        // Ensure there is something to listen to
        if (empty($sockets)) {
            return false;
        }
        
        // Listen
        $result = $this->_listen($sockets, $timeout);

        // Condense
        $result = $this->_condense($result);

        // Close
        $this->_close($sockets);

        return $result;
    }

    
    /**
     * Open a socket
     *
     * @param       array       $addr           Address to connect to
     * @return      array       A connection resource or FALSE if connect failed
     */
    private function _open($addr, $port)
    {
        // Get address
        $addr = $this->_getip($addr);
        if ($addr === false) {
            return false;
        }

        // Open socket
        $errno = null;
        $errstr = null;
        $socket = fsockopen('udp://' . $addr, $port, $errno, $errstr, 1);

        // Non blocking
        if ($socket !== false) {
            stream_set_blocking($socket, false);
        }

        return $socket;
    }


    /**
     * Write to a sockets
     *
     * @param       resource    $socket         Socket to write to
     * @param       string      $packet         Packet to be written
     */
    private function _write($socket, $packet)
    {
        fwrite($socket, $packet);
    }


    /**
     * Listen to an array of sockets
     *
     * @param       array       $sockets        Array of sockets
     * @param       int         $timeout        Maximum wait time (milliseconds)
     * @return      array       An array of result data
     */
    private function _listen($sockets, $timeout)
    {
        // Init
        $loops = 0;
        $maxloops = 20;
        $result = array();
        $starttime = microtime(true);
        $r = $sockets;

        // Listen
        while (stream_select($r, $w = null, $e = null, 0,
            ($timeout * 1000) - ((microtime(true) - $starttime) * 1000000)) !== 0) {

            // Make sure we don't repeat too many times
            if (++$loops > $maxloops) {
                break;
            }

            // For each socket that had activity read a single packet
            foreach ($r as $socket) {
                $response = stream_socket_recvfrom($socket, 2048);
                
                if ($response === false) {
                    continue;
                }
                
                $id = $this->_getserverkey($socket);
                $result[$id][] = $response;
            }

            // Reset the listening array
            $r = $sockets;
        }

        return $result;
    }


    /**
     * Condenses server replies
     *
     * Moves single packet replies into a string and leaves multipacket
     * responses as an array.
     *
     * @param       array       $packets        Array of packets
     * @return      array       Array of packets
     */
    private function _condense($packets)
    {
        foreach ($packets as $key => $packet) {
            if (count($packet) === 1) {
                $packets[$key] = $packet[0];
            }
        }

        return $packets;
    }


    /**
     * Close each socket
     *
     * @param       string      $sockets        Array of sockets
     * @return      void
     */
    private function _close($sockets)
    {
        foreach ($sockets as $socket) {
            fclose($socket);
        }
    }
    
        
    /**
     * Find the server key for a given resource
     *
     * @param       resource    $resourceid     Resource
     * @return      int         Server key
     */
    private function _getserverkey($resource)
    {
        if (isset($this->_serverkeys[(int) $resource])) {
            return $this->_serverkeys[$resource];
        }
        
        // If we're here the socket was not opened properly
        return -1;
    }
    
        
    /**
     * Get the address to connect to
     *
     * @param       string      $addr           An IP or hostname
     * @return      string      An IP address, or FALSE if address was not valid
     */
    private function _getip($addr)
    {
        // If it isn't a valid IP assume it is a hostname
        $preg = '#^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}' . 
            '(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$#';
        if (!preg_match($preg, $addr)) {
            $res = gethostbyname($addr);

            // Not a valid host nor IP
            if ($res === $addr) {
                $res = false;
            }
        } else {
            $res = $addr;
        }

        return $res;
    }
    
}

?>
