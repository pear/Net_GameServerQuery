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


require_once 'GameServerQuery\Protocol.php';


/**
 * Processing class
 *
 * This class wraps around the processing methods.
 * Calls the normalise and protocol classes for each game.
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
    private $_config;

    
    /**
     * Array holding all the loaded protocol objects
     *
     * @var         array
     */
    private $_protocols;


    /**
     * Constructor
     */
    public function __construct($config)
    {
        $this->_config    = $config;
        $this->_protocols = array();
    }
 


    /**
     * Batch process all the results
     *
     * @param  array  $results  Query results
     * @return array  Processed results
     */
    public function process($results)
    {
        // Init
        $newresults = array();
 
        // Process
        foreach ($results as $key => $result) {
           
            // Load the object if it is not loaded
            if (!key_exists($result['protocol'], $this->_protocols)) {
                
                // Load the protocol class
                $filename = 'GameServerQuery/Protocol/' . $result['protocol'] . '.php';

                if (include_once $filename) {
                    $classname = 'Net_GameServerQuery_Protocol_' .  $result['protocol'];
                    $this->_protocols[$result['protocol']] = new $classname;
                }
                else {
                    throw new Exception('Protocol driver not found');
                }
            }
            
            $newresults[$key] = $this->processOnce($result);
        }

        return $newresults;
    }


    /**
     * Process a single result
     *
     * @param  array  $results  Query result
     * @return array  Processed result
     */
    public function processOnce($result)
    {
        // Parse the response
        $parsed = $this->_protocols[$result['protocol']]->process($result['packetname'], $result['response']);

        // Normalise the response
        $result = $this->normalise($result['protocol'], $result['flag'], $parsed);

        return $result;
    }

    
    /**
     * Normalise result arrays
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

        $keys = $this->_config->normal();
        $normals = $this->_config->normal($protocol);

        for ($i = 0, $x = count($keys); $i !== $x; $i++) {
            $ndata[$keys[$i]] = ($normals[$i] === false) ? $normals[$i] : $data[$normals[$i]];
        }

        return $ndata;
    }

}
?>
