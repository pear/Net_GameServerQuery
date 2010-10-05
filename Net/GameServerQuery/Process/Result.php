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
 * Provide an interface for easy storage of a parsed server response
 *
 * @category Net
 * @package  Net_GameServerQuery
 * @author   Aidan Lister <aidan@php.net>  
 * @author   Tom Buskens <ortega@php.net>
 * @license  PHP 3.0 http://www.php.net/license/3_0.txt
 * @link     http://pear.php.net/package/Net_GameServerQuery
 */
class Net_GameServerQuery_Process_Result
{

    /**
     * Formatted server response
     *
     * @var        array
     */
    private $_result;
    
    /**
     * Highest player index
     *
     * @var        int
     */
    private $_pindex = 0;
    
    /**
     * If additional meta info should be included
     *
     * @var        bool
     */
    private $_showmeta;
    
    
    /**
     * Constructor
     *
     * @param bool $showmeta If additional meta info should be included     
     */
    public function __construct($showmeta)
    {
        $this->_showmeta = $showmeta;   
    }


    /**
     * Adds variable to results
     *
     * @param string $name  Variable name
     * @param string $value Variable value
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
     * @param string $name  Variable name
     * @param string $value Variable value
     *
     * @return      void
     */
    public function addMeta($name, $value)
    {
        if ($this->_showmeta === true) {
            $this->_result['__' . $name] = $value;
        }
    }


    /**
     * Adds player variable to output
     *
     * @param string $name  Variable name
     * @param string $value Variable value
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
