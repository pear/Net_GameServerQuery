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
 * Provide an interface for easy storage of a parsed server response
 *
 * @category       Net
 * @package        Net_GameServerQuery
 * @author         Aidan Lister <aidan@php.net>
 * @author         Tom Buskens <ortega@php.net>
 * @version        $Revision$
 */
class Net_GameServerQuery_Result
{

    /**
     * Formatted server response
     *
     * @var        array
     * @access     public
     */
    private $_result;

    /**
     * Highest player index
     *
     * @var        int
     * @access     public
     */
    private $_pindex = 0;


    /**
     * Adds variable to results
     *
     * @param      string    $name     Variable name
     * @param      string    $value    Variable value
     */
    public function add($name, $value)
    {
        $this->_result[$name] = $value;
    }


    /**
     * Adds meta information to the results
     *
     * Currently prefixes key with __
     *
     * @param      string    $name     Variable name
     * @param      string    $value    Variable value
     */
    public function addMeta($name, $value)
    {
        $this->_result['__' . $name] = $value;
    }


    /**
     * Adds player variable to output
     *
     * @param   string   $name   Variable name
     * @param   string   $value  Variable value
     */
    public function addPlayer($name, $value)
    {
        // Player var is already set, so it must belong to the next player
        if (isset($this->_result[$this->_pindex][$name])) {
            ++$this->_pindex;
        }
        
        // Set player var
        $this->_result[$this->_pindex][$name] = $value;
    }


    /**
     * Return the results
     *
     * @return  array       The results
     */
    public function fetch()
    {
        return $this->_result;
    }

}

?>