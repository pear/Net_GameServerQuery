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
 * Hold runtime configuration options
 *
 * @category        Net
 * @package         Net_GameServerQuery
 * @author          Aidan Lister <aidan@php.net>
 * @author          Tom Buskens <ortega@php.net>
 * @version         $Revision$
 */
class Net_GameServerQuery_Config
{
    /**
     * Hold an array of runtime options
     *
     * @var         array
     */
    private $_options = array();


    /**
     * Set an option
     *
     * Can be one of:
     * - normalise      Reduces the information returned in status to a standard subset
     * - showmeta       Shows information not directly returned by protocol (__count etc)
     * - timeout        Sets length of time to wait for server replies
     *
     * @param    string     $option       The option to set
     * @param    string     $value        The value
     */
    public function setOption($option, $value)
    {
        switch ($option) {
            case 'normalise':
            case 'showmeta':
            case 'timeout':
                $this->_options[$option] = $value;
                break;
            
            default:
                throw new InvalidOptionException($option);
        }
    }


    /**
     * Get an option
     *
     * @param    string     $option       The option to get
     */
    public function getOption($option)
    {
        switch ($option) {
            case 'normalise':
            case 'showmeta':
            case 'timeout':
                return $this->_options[$option];
                break;
            
            default:
                throw new InvalidOptionException($option);
        }
    }

}

?>