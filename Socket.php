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
    public function go ($servers, $timeout)
    {
        $this->_servers = $servers;

		$this->open($this->_servers);
		$result = $this->listen($timeout);
		$this->close();

        return $result;
    }


	/**
     * Open
     */
	public function open ($servers)
	{
        foreach ($servers as $key => $server)
        {

			// Open each socket
			$socket = @fsockopen("udp://" . $server['ip'], $server['port'], $errno, $errstr, 1);
			if ($socket !== false)
			{
				stream_set_blocking($socket, false);

				$sockets[$key] = $socket;
				$sockets_list[(int)$socket] = $key;

				foreach ($server['query'] as $packet) {
					$this->write($socket, $packet);
				}
			}
        }

		$this->_sockets = $sockets;
		$this->_sockets_list = $sockets_list;

		return true;
	}


	/**
	 * Write
	 */
	public function write ($socket, $packet)
	{
		fwrite($socket, $packet);
	}


	/**
	 * Listen
	 */
	public function listen ($timeout)
	{
		// Listen to sockets
		$result = array ();
		$starttime = microtime(true);
		$r = $this->_sockets;
		while (stream_select($r, $w = null,	$e = null, 0, $timeout - ((microtime(true) - $starttime) * 1000000)) !== 0)  
		{
			foreach ($r as $socket) {
				$response = fread($socket, 2048);
				$result[$this->_sockets_list[$socket]][] = $response;
			}
			
			$r = $this->_sockets;
		}

		return $result;
	}


    /**
     * Close
     */
	public function close ()
	{
		foreach ($this->_sockets as $socket) {
			fclose($socket);
		}
	}

}

?>