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
 * Process the server responses
 *
 * Loads the protocol drivers to parse packets, and runs
 * the normalistion methods
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Process
{
    
    /**
     * Hold an instance of the config class
     *
     * @var         object
     */
    private $_gamedata;

    
    /**
     * Array holding all the loaded protocol objects
     *
     * @var         array
     */
    private $_protocols;


    /**
     * Constructor
     */
    public function __construct($gamedata)
    {
        $this->_gamedata  = $gamedata;
        $this->_protocols = array();
    }
 
    
    /**
     * Factory for including protocols
     */
    static public function &factory($protocol)
    {
        $filename   = NET_GAMESERVERQUERY_BASE . 'Protocol/' . $protocol . '.php';
        $classname  = 'Net_GameServerQuery_Protocol_' .  $protocol;

        if (file_exists($filename)) {
            include_once $filename;
            return new $classname;
        }

        throw new DriverNotFoundException;
    }

    /**
     * Batch process all the results
     *
     * @param  array  $results  Query results
     * @return array  Processed results
     */
    public function batch($results)
    {
        $processed = array();
        foreach ($results as $key => $result) {
            $processed[$key] = $this->process($result);
        }

        return $processed;
    }


    /**
     * Process a single result
     *
     * @param  array  $results  Query result
     * @return array  Processed result
     */
    public function process($result)
    {
        // No reply means no parsing
        if ($result['response'] === false) {
            return false;
        }

        // Load the protocol file into cache
        if (!key_exists($result['protocol'], $this->_protocols)) {
            $classname = self::factory($result['protocol']);
            $this->_protocols[$result['protocol']] = new $classname;
        }
        
        // Parse the response
        $protocol =& $this->_protocols[$result['protocol']];
        $response = $result['response'];
        $parsed = $protocol->parse($result['packetname'], $response);

        // Normalise the response
        $result = $this->normalise($result['protocol'], $result['flag'], $parsed);

        return $result;
    }

    
    /**
     * Normalise result arrays
     *
     * This only normalises the status array
     *
     * @param  string  $protocol  Protocol name
     * @param  string  $flag      Protocol flag
     * @param  array   $data      Response data
     */
    public function normalise($protocol, $flag, $data)
    {
        if ($flag !== 'status') {
            return $data;
        }

        $keys = $this->_gamedata->getProtocolNormals();
        $normals = $this->_gamedata->getProtocolNormals($protocol);

        // If no normal keys are set return all
        if (empty($normals)) { 
            return $data;
        }

        $ndata = array();
        for ($i = 0, $x = count($keys); $i < $x; $i++) {
            $d = isset($data[$normals[$i]]) ? $data[$normals[$i]] : false;
            $ndata[$keys[$i]] = ($normals[$i] === false) ? $normals[$i] : $d;
        }

        return $ndata;
    }

}

?>