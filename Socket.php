<?php
/**
 * The socket class
 */
class Net_GameServerQuery_Socket
{
    /**
     * The sockets / list of sockets
     */
	private $_sockets;
	private $_sockets_list;


    /**
     * Do the whole lot
     */
    public function batchquery($servers, $timeout)
    {
		list($sockets, $sockets_list) = $this->open($servers);
		$result = $this->listen($sockets, $sockets_list, $timeout);
		$this->close($sockets);

        return $result;
    }


	/**
     * Open
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

        return array($sockets, $sockets_list);
	}


	/**
	 * Write
	 */
	public function write($socket, $packet)
	{
		return fwrite($socket, $packet);
	}


	/**
	 * Listen
	 */
	public function listen($sockets, $sockets_list, $timeout)
	{
		$result = array();
		$starttime = microtime(true);
		$r = $sockets;

        // If we have no sockets don't bother
        if (empty($sockets)) {
            return array();
        }

		// Listen to sockets
		while (stream_select($r, $w = null,	$e = null, 0, $timeout - ((microtime(true) - $starttime) * 1000000)) !== 0)
		{
			foreach ($r as $socket) {
				$response = fread($socket, 2048);
				$result[$sockets_list[$socket]][] = $response;
			}
			
			$r = $sockets;
		}

		return $result;
	}


    /**
     * Close
     */
	public function close($sockets)
	{
		foreach ($sockets as $socket) {
			fclose($socket);
		}
	}

}

?>