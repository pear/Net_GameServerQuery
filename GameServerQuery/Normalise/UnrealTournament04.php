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


class Net_GameServerQuery_Normalise_UnrealTournament04 extends Net_GameServerQuery_Normalise
{
    public function process($query, $data)
    {
        switch ($query) :
            case 'status':
                $normal['password'] = $data['password'];
                $normal['players'] = $data['players'];
                $normal['maxplayers'] = $data['max'];
                $normal['hostname'] = $data['hostname'];
                $normal['map'] = $data['map'];
                break;

            case 'players':
                if (empty($data['playerid'])) {
                    return false;
                }
                
                $normal = array();
                foreach ($data['playerid'] as $key => $value) {
                    $normal[] = array (
                        'name' => $data['playername'][$key],
                        'score' => $data['playerscore'][$key],
                        'time' => (int) $data['playertime'][$key]
                    );
                }
                break;
          
            case 'rules':
                unset($data['rowcount']);
                ksort($data);
                $normal = $data;
                break;

            case 'ping':
                $normal = $data;
                break;

        endswitch;

        return $normal;
    }
}

?>
