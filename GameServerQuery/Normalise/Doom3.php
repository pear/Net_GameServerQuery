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


class Net_GameServerQuery_Normalise_Doom3 extends Net_GameServerQuery_Normalise
{
    public function process($query, $data)
    {
        // Players
        $vars = array('id', 'name', 'team', 'ping');
        $players = array();

        foreach($vars as $var) {

            if (isset($data[$var])) {

                $value = $data[$var];
                unset($data[$var]);

                if (is_array($value)) {
                    for ($i = 0, $x = count($value); $i !== $x; $i++) {
                        $players[$i][$var] = $value[$i];
                    }

                }
                else {
                    $players[0][$var] = $value;
                }
            }
        }

        if (!empty($players)) {
            $data['players'] = $players;
        }

        return $data;
    }
}

?>