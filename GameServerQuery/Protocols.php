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


$protocol['halflife']['name']				= 'Half-Life';
$protocol['halflife']['packet']				= "\xFF\xFF\xFF\xFF%s\x00";
$protocol['halflife']['queryport']			= '27015';
$protocol['halflife']['send']['status']		= 'infostring';
$protocol['halflife']['send']['players']	= 'players';
$protocol['halflife']['send']['rules']		= 'rules';
$protocol['halflife']['send']['ping']		= 'ping';

$protocol['gamespy']['name']				= 'GameSpy';
$protocol['gamespy']['packet']				= '%s';
?>