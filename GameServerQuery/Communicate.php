<?php
/**
 * Handles communication to and from servers
 *
 * @category        Net
 * @package         Net_GameServerQuery_Communicate
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
        // Open and write
        list($sockets, $sockets_list) = $this->open($servers);

        // Listen
        $result = $this->listen($sockets, $sockets_list, $timeout);

        // Close
        $this->close($sockets);

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
        $sockets_list = array();

        foreach ($servers as $key => $server)
        {
            // Open each socket
            $ip = "udp://" . $server['ip'];
            $socket = @fsockopen($ip, $server['port'], $errno, $errstr, 1);
            if ($socket !== false)
            {
                stream_set_blocking($socket, false);

                $sockets[$key] = $socket;
                $sockets_list[(int)$socket] = $key;

                // Need some error checking here
                foreach ($server['query'] as $packet) {
                    $this->write($socket, $packet);
                }
            }
        }

        // Return an array of sockets, and an array of socket identifiers
        return array($sockets, $sockets_list);
    }


    /**
     * Write a packet to a socket
     *
     * @param       resource    $socket     The socket
     * @param       string      $packet     The packet
     * @return      bool        True if the packet was written
     */
    public function write($socket, $packet)
    {
        return fwrite($socket, $packet);
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

        // Listen to sockets
        $loops = 0;
        $maxloops = 30;
        $result = array();
        $starttime = microtime(true);
        $r = $sockets;
        while (stream_select($r, $w = null, $e = null, 0,
            // Calculate a variable timeout to preserve the timeout
            $timeout - ((microtime(true) - $starttime) * 1000000)) !== 0) 
        {
            // Make sure we don't repeat too many times
            if (++$loops > $maxloops) {
                break;
            }

            // For each socket that had activity, read a single packet
            foreach ($r as $socket) {
                $response = stream_socket_recvfrom($socket, 2048);
                $result[$sockets_list[$socket]][] = $response;
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

}

?>