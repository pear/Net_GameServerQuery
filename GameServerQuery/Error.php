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
 * ParsingException
 */
class ParsingException extends Exception
{
    public function __construct($packetname)
    {
        parent::__construct('Unable to parse "' . $packetname . '" packet');
    }
}


/**
 * InvalidFlagException
 */
class InvalidFlagException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid query flag');
    }
}


/**
 * InvalidPacketException
 */
class InvalidPacketException extends Exception
{
    public function __construct($packetname)
    {
        parent::__construct('Protocol does not support parsing of the "'. $packetname .'" packet');
    }
}


/**
 * DriverNotFoundException
 */
class DriverNotFoundException extends Exception
{
    public function __construct($protocol)
    {
        parent::__construct('The protocol driver "'. $protocol .'"  required for this game is missing');
    }
}


/**
 * DriverNotFoundException
 */
class InvalidGameException extends Exception
{
    public function __construct()
    {
        parent::__construct('Invalid Game');
    }
}


/**
 * InvalidServerException
 */
class InvalidServerException extends Exception
{
    public function __construct()
    {
        parent::__construct('Unable to connect');
    }
}

?>