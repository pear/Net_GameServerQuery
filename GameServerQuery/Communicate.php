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
 * Sends and recieves information from servers
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Communicate
{
    /**
     * Perform a batch query
     *
     * This runs open, write, listen and close sequentially
     *
     * @param       array   $servers    An array of server data
     * @param       int     $timeout    A timeout in milliseconds  
     * @return      array   An array of results
     */
    public function query($servers, $timeout)
    {
        // Open
        list($sockets, $packets, $sockets_list) = $this->open($servers);

        // Write
        $this->write($sockets, $packets);

        // Listen
        $result = $this->listen($sockets, $sockets_list, $timeout);

        // Close
        $this->close($sockets);

        // Concat
        $result = $this->concat($result);
        
        return $result;
    }


    /**
     * Open the sockets
     *
     * @param       array       $servers     An array of server data  
     * @return      array       An array of sockets and an array of corresponding keys
     */
    public function open($servers)
    {
        $sockets = array();
        $packets = array();
        $sockets_list = array();

        foreach ($servers as $key => $server)
        {
            $addr = $server['addr'];
            if (strspn($addr, '.0123456789') !== strlen($server['addr'])) {
                $addr = gethostbyname($server['addr']);
            }

            // Open each socket
            $socket = @fsockopen('udp://' . $addr, $server['port'], $errno, $errstr, 1);
            if ($socket !== false) {
                stream_set_blocking($socket, false);

                $sockets[$key] = $socket;
                $packets[$key] = $server['packet'];
                $sockets_list[(int) $socket] = $key;

            }
        }

        // Return an array of sockets, and an array of socket identifiers
        return array($sockets, $packets, $sockets_list);
    }


    /**
     * Write to an array of sockets
     *
     * @param       array       $sockets        An array of sockets
     * @param       array       $packets        An array of packets
     */
    public function write($sockets, $packets)
    {
        // If we have no sockets don't bother
        if (empty($sockets)) {
            return array();
        }

        // Write packet to each of the sockets
        foreach($sockets as $key => $socket) {
            fwrite($socket, $packets[$key]);
        }
    }


    /**
     * Listen to an array of sockets
     *
     * @param       array       $sockets        An array of sockets
     * @param       array       $sockets_list   An array of socket relationships
     * @param       int         $timeout        The maximum time to listen for
     * @return      array       An array of result data
     */
    public function listen($sockets, $sockets_list, $timeout)
    {
        // If we have no sockets don't bother
        if (empty($sockets)) {
            return array();
        }

        // Initialise enviroment
        $loops = 0;
        $maxloops = 30;
        $result = array();
        $starttime = microtime(true);
        $r = $sockets;

        // Listen to sockets for any activity
        while (stream_select($r, $w = null, $e = null, 0,
            $timeout - ((microtime(true) - $starttime) * 1000000)) !== 0) 
        {
            // Make sure we don't repeat too many times
            if (++$loops > $maxloops) {
                break;
            }

            // For each socket that had activity, read a single packet
            foreach ($r as $socket) {
                $response = stream_socket_recvfrom($socket, 2048);
                $key = $sockets_list[(int) $socket];
                $result[$key][] = $response;
            }
            
            // Reset the listening array
            $r = $sockets;
        }

        return $result;
    }


    /**
     * Close each socket
     *
     * @param       string      $sockets        An array of sockets
     * @return      void
     */
    public function close($sockets)
    {
        foreach ($sockets as $socket) {
            fclose($socket);
        }
    }


    /**
     * Concatonate multiple packets
     *
     * @param       array       $packets        An array of packets
     * @return      array       An array of joined packets
     */
    public function concat($packets)
    {
        $newresults = array();

        foreach ($packets as $key => $packet)
        {
            // Check if we got multiple packets back from the server
            if (count($packet) > 1) {
                
                // We need to sort the array with the biggest packet first
                usort($packet, array($this, '_sortbylength'));
            }

            $newresults[$key] = implode($packet);
        }

        return $newresults;
    }


    /**
     * Sorts a packet list by length
     * 
     * @param       string      $a        Compare 1
     * @param       string      $b        Compare 2
     * @return      int         -1, 0 or 1
     */
    private function _sortbylength($a, $b)
    {    
        if ($a == $b) {
            return 0;
        }

        return (strlen($a) > strlen($b)) ? -1 : 1;
    }

}

?>
