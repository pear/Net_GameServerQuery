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
require_once 'GameServerQuery\Normalise.php';


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
     * Array holding all the loaded protocol objects
     */
    private $_protocols = array();


    /**
     * Batch process all the results
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
                if (include_once "GameServerQuery/Protocol/{$result['protocol']}.php") {
                    $protocol_classname = "Net_GameServerQuery_Protocol_{$result['protocol']}";
                    $this->_protocols[$result['protocol']] = new $protocol_classname;
                } else {
                    throw new Exception ('Protocol driver not found');
                }
            }
            
            $newresults[$key] = $this->process_once($result);
        }

        return $newresults;
    }


    /**
     * Process a single result
     */
    public function process_once($result)
    {
        // Parse the response
        $parsed = $this->_protocols[$result['protocol']]->process($result['packetname'], $result['packet']);

        // Normalise the response
        $result = $parsed;

        return $result;
    }

}

?>