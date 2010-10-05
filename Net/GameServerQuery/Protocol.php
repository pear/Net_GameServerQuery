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


require_once NET_GAMESERVERQUERY_BASE . 'Process/Buffer.php';
require_once NET_GAMESERVERQUERY_BASE . 'Process/Result.php';


/**
 * Abstract class which all protocol classes must inherit
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */
abstract class Net_GameServerQuery_Protocol
{
    /**
     * Parse server response according to packet type
     *
     * @param string       $packetname Name of the packet
     * @param string|array $response   Packet
     * @param bool         $config     Config
     *
     * @access     public
     * @return     array            Array containing formatted server response
     */
    public function parse($packetname, $response, $config)
    {
        // Init
        $callback = array($this, $packetname);

        // Sanity check
        if (!is_callable($callback)) {
            throw new InvalidPacketException($packetname);
        }
                
        // If the response is an array then multiple packets were recieved
        // Ask the protocol to join them together as a single response
        if (is_array($response)) {
            $response = $this->multipacketjoin($response);
        }
           
        // Create buffer
        $response = new Net_GameServerQuery_Process_Buffer($response);
        
        // Configure result enviroment
        $showmeta = $config->getOption('showmeta');
        $result   = new Net_GameServerQuery_Process_Result($showmeta);

        // Parse packet
        $result = call_user_func(array($this, $packetname), $response, $result);

        // Check for error
        if ($result === false) {
            throw new ParsingException($packetname);
        }

        return $result;
    }

    
    /**
     * Join multiple packet responses into a single response
     *
     * This is usually overrided by the protocol specific method
     *
     * @param array $packets Array containing the packets
     *
     * @access     public
     * @return     string       Joined server response
     */
    protected function multipacketjoin($packets)
    {
        return implode('', $packets);
    }
}

?>
