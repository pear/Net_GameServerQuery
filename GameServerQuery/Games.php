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


// Packet settings

$packets['Doom3']['players']        = array('getInfo', "\xFF\xFFgetInfo\x00\x00\x00\x00\x00");
$packets['Doom3']['rules']          = array('getInfo', "\xFF\xFFgetInfo\x00\x00\x00\x00\x00");
$packets['Doom3']['status']         = array('getInfo', "\xFF\xFFgetInfo\x00\x00\x00\x00\x00");

$packets['FarCry']['players']       = array('players', "\x7f\xff\xff\xffplayers");
$packets['FarCry']['rules']         = array('rules',   "\x7f\xff\xff\xffrules");
$packets['FarCry']['status']        = array('status',  "\x7f\xff\xff\xffstatus");

$packets['GameSpy']['players']      = array('players', "\\players\\");
$packets['GameSpy']['rules']        = array('status',  "\\status\\");
$packets['GameSpy']['status']       = array('status',  "\\status\\");

$packets['GameSpy04']['players']    = array('players', "\xFE\xFD\x00NGSQ\x00\xFF\xFF");
$packets['GameSpy04']['rules']      = array('status',  "\xFE\xFD\x00NGSQ\xFF\x00\x00");
$packets['GameSpy04']['status']     = array('status',  "\xFE\xFD\x00NGSQ\xFF\x00\x00");

$packets['HalfLife']['players']     = array('players',    "\xFF\xFF\xFF\xFFplayers");
$packets['HalfLife']['rules']       = array('rules',      "\xFF\xFF\xFF\xFFrules");
$packets['HalfLife']['status']      = array('infostring', "\xFF\xFF\xFF\xFFinfostring");

$packets['Unreal2']['players']      = array('players', "\x78\x00\x00\x00\x02");
$packets['Unreal2']['rules']        = array('rules',   "\x78\x00\x00\x00\x01");
$packets['Unreal2']['status']       = array('status',  "\x78\x00\x00\x00\x00");


// Game settings

$games['aa']['name']                = 'America\'s Army: Operations';
$games['aa']['protocol']            = 'GameSpy';
$games['aa']['queryport']           = '1717';

$games['avp2']['name']              = 'Alien vs Predator 2';
$games['avp2']['protocol']          = 'GameSpy';
$games['avp2']['queryport']         = '27888';

$games['bf1942']['name']            = 'Battlefield 1942';
$games['bf1942']['protocol']        = 'GameSpy';
$games['bf1942']['queryport']       = '23000';

$games['bfvietnam']['name']         = 'BattleField: Vietnam';
$games['bfvietnam']['protocol']     = 'GameSpy04';
$games['bfvietnam']['queryport']    = '23000';

$games['breed']['name']             = 'Breed';
$games['breed']['protocol']         = 'Breed';
$games['breed']['queryport']        = '7649';

$games['cod']['name']               = 'Call of Duty';
$games['cod']['protocol']           = 'mohq3';
$games['cod']['queryport']          = '28960';

$games['ccren']['name']             = 'Command & Conquer: Renegade';
$games['ccren']['protocol']         = 'GameSpy';
$games['ccren']['queryport']        = '25300';

$games['cjack']['name']             = 'Contract J.A.C.K.';
$games['cjack']['protocol']         = 'GameSpy';
$games['cjack']['queryport']        = '27888';

$games['dai']['name']               = 'Daikatana';
$games['dai']['protocol']           = 'GameSpy';
$games['dai']['queryport']          = '27992';

$games['dx']['name']                = 'Deus Ex';
$games['dx']['protocol']            = 'GameSpy';
$games['dx']['queryport']           = '7791';

$games['dev']['name']               = 'Devastation';
$games['dev']['protocol']           = 'ut2003';
$games['dev']['queryport']          = '7778';

$games['doom3']['name']             = 'Doom 3';
$games['doom3']['protocol']         = 'Doom3';
$games['doom3']['queryport']        = '27666';

$games['drakan']['name']            = 'Drakan: Order of the Flame';
$games['drakan']['protocol']        = 'GameSpy';
$games['drakan']['queryport']       = '27046';

$games['fc']['name']                = 'FarCry';
$games['fc']['protocol']            = 'fc';
$games['fc']['queryport']           = '49001';

$games['fl']['name']                = 'Freelancer';
$games['fl']['protocol']            = 'fl';
$games['fl']['queryport']           = '2302';

$games['giants']['name']            = 'Giants: Citizen Kabuto';
$games['giants']['protocol']        = 'GameSpy';
$games['giants']['queryport']       = '8911';

$games['gobs']['name']              = 'Global Operations';
$games['gobs']['protocol']          = 'GameSpy';
$games['gobs']['queryport']         = '28672';

$games['gore']['name']              = 'Gore';
$games['gore']['protocol']          = 'GameSpy';
$games['gore']['queryport']         = '27778';

$games['gr']['name']                = 'Ghost Recon';
$games['gr']['protocol']            = 'gr';
$games['gr']['queryport']           = '2348';

$games['halo']['name']              = 'Halo: Combat Evolved';
$games['halo']['protocol']          = 'GameSpy';
$games['halo']['queryport']         = '2302';

$games['halflife']['name']          = 'Half-Life';
$games['halflife']['protocol']      = 'HalfLife';
$games['halflife']['queryport']     = '27015';

$games['hw2']['name']               = 'Homeworld 2';
$games['hw2']['protocol']           = 'hw2';
$games['hw2']['queryport']          = '6500';

$games['hx2']['name']               = 'Hexen 2';
$games['hx2']['protocol']           = 'hx2';
$games['hx2']['queryport']          = '26900';

$games['igi2']['name']              = 'IGI 2';
$games['igi2']['protocol']          = 'GameSpy';
$games['igi2']['queryport']         = '26001';

$games['jk2']['name']               = 'Jedi Knight 2: Jedi Outcast';
$games['jk2']['protocol']           = '';
$games['jk2']['queryport']          = '28070';

$games['jkja']['name']              = 'Jedi Knight: Jedi Academy';
$games['jkja']['protocol']          = 'q3';
$games['jkja']['queryport']         = '29070';

$games['kp']['name']                = 'Kingpin: Life of Crime';
$games['kp']['protocol']            = 'q3';
$games['kp']['queryport']           = '31510';

$games['mohaa']['name']             = 'Medal of Honor';
$games['mohaa']['protocol']         = 'q3';
$games['mohaa']['queryport']        = '12300';

$games['mta']['name']               = 'Multi Theft Auto: Vice City';
$games['mta']['protocol']           = 'mta';
$games['mta']['queryport']          = '2126';

$games['nightfire']['name']         = 'James Bond: Nightfire';
$games['nightfire']['protocol']     = 'HalfLife';
$games['nightfire']['queryport']    = '27015';

$games['nitro']['name']             = 'Nitro Family';
$games['nitro']['protocol']         = 'GameSpy';
$games['nitro']['queryport']        = '25601';

$games['nwn']['name']               = 'Neverwinter Nights';
$games['nwn']['protocol']           = 'GameSpy';
$games['nwn']['queryport']          = '5121';

$games['nolf']['name']              = 'No One Lives Forever';
$games['nolf']['protocol']          = 'GameSpy';
$games['nolf']['queryport']         = '27888';

$games['nolf2']['name']             = 'No One Lives Forever 2';
$games['nolf2']['protocol']         = 'GameSpy';
$games['nolf2']['queryport']        = '27890';

$games['of']['name']                = 'Operation Flashpoint';
$games['of']['protocol']            = 'GameSpy';
$games['of']['queryport']           = '6073';

$games['p2']['name']                = 'Postal 2';
$games['p2']['protocol']            = 'GameSpy';
$games['p2']['queryport']           = '7778';

$games['painkiller']['name']        = 'Pain Killer';
$games['painkiller']['protocol']    = 'GameSpy04';
$games['painkiller']['queryport']   = '3455';

$games['qw']['name']                = 'QuakeWorld';
$games['qw']['protocol']            = 'qw';
$games['qw']['queryport']           = '27500';

$games['q2']['name']                = 'Quake 2';
$games['q2']['protocol']            = 'q3';
$games['q2']['queryport']           = '27910';

$games['q3']['name']                = 'Quake 3 Arena';
$games['q3']['protocol']            = 'q3';
$games['q3']['queryport']           = '27960';

$games['rf']['name']                = 'Red Faction';
$games['rf']['protocol']            = 'rf';
$games['rf']['queryport']           = '7755';

$games['ron']['name']               = 'Rise of nations';
$games['ron']['protocol']           = 'GameSpy';
$games['ron']['queryport']          = '6501';

$games['r6']['name']                = 'Rainbow Six';
$games['r6']['protocol']            = 'GameSpy';
$games['r6']['queryport']           = '2346';

$games['rtcw']['name']              = 'Return to Castle Wolfenstein';
$games['rtcw']['protocol']          = 'q3';
$games['rtcw']['queryport']         = '27960';

$games['rs']['name']                = 'Rogue Spear';
$games['rs']['protocol']            = 'spy1';
$games['rs']['queryport']           = '2346';

$games['rune']['name']              = 'Rune';
$games['rune']['protocol']          = 'GameSpy';
$games['rune']['queryport']         = '7778';

$games['rvs']['name']               = 'RavenShield';
$games['rvs']['protocol']           = 'rvs';
$games['rvs']['queryport']          = '8777';

$games['sav']['name']               = 'Savage: The Battle For Newerth';
$games['sav']['protocol']           = 'sav';
$games['sav']['queryport']          = '11235';

$games['shogo']['name']             = 'Shogo: Armored Division';
$games['shogo']['protocol']         = 'GameSpy';
$games['shogo']['queryport']        = '27888';

$games['sin']['name']               = 'SIN';
$games['sin']['protocol']           = 'q3';
$games['sin']['queryport']          = '22450';

$games['sof']['name']               = 'Soldier of Fortune';
$games['sof']['protocol']           = 'q3';
$games['sof']['queryport']          = '28910';

$games['GameSpy']['name']           = 'Soldier of Fortune 2: Double Helix';
$games['GameSpy']['protocol']       = 'q3';
$games['GameSpy']['queryport']      = '20100';

$games['GameSpy']['name']           = 'Generic Gamespy Server';
$games['GameSpy']['protocol']       = 'GameSpy';
$games['GameSpy']['queryport']      = '99999';

$games['ss']['name']                = 'Serious Sam';
$games['ss']['protocol']            = 'GameSpy';
$games['ss']['queryport']           = '25601';

$games['ss2']['name']               = 'Serious Sam: The Second Encounter';
$games['ss2']['protocol']           = 'GameSpy';
$games['ss2']['queryport']          = '25601';

$games['starsiege']['name']         = 'Starsiege';
$games['starsiege']['protocol']     = 'starsiege';
$games['starsiege']['queryport']    = '29001';

$games['mstarsiege']['name']        = 'Starsiege Master';
$games['mstarsiege']['protocol']    = 'mstarsiege';
$games['mstarsiege']['queryport']   = '29000';

$games['stvef']['name']             = 'Star Trek Voyager: Elite Force';
$games['stvef']['protocol']         = 'q3';
$games['stvef']['queryport']        = '27960';

$games['stvef2']['name']            = 'Star Trek Voyager: Elite Force 2';
$games['stvef2']['protocol']        = 'q3';
$games['stvef2']['queryport']       = '29253';

$games['tribes']['name']            = 'Starsiege: Tribes';
$games['tribes']['protocol']        = 'tribes';
$games['tribes']['queryport']       = '28001';

$games['tops']['name']              = 'Tactical Operations';
$games['tops']['protocol']          = 'GameSpy';
$games['tops']['queryport']         = '7778';

$games['tf']['name']                = 'Team Factor';
$games['tf']['protocol']            = 'GameSpy';
$games['tf']['queryport']           = '57778';

$games['thps']['name']              = 'Tony Hawk\'s Pro Skater 3/4';
$games['thps']['protocol']          = 'GameSpy';
$games['thps']['queryport']         = '6500';

$games['tribes2']['name']           = 'Tribes 2';
$games['tribes2']['protocol']       = 'GameSpy';
$games['tribes2']['queryport']      = '28001';

$games['tron2']['name']             = 'Tron 2';
$games['tron2']['protocol']         = 'GameSpy';
$games['tron2']['queryport']        = '27888';

$games['unreal']['name']            = 'Unreal';
$games['unreal']['protocol']        = 'GameSpy';
$games['unreal']['queryport']       = '7778';

$games['u2xmp']['name']             = 'Unreal 2 XMP';
$games['u2xmp']['protocol']         = 'u2xmp';
$games['u2xmp']['queryport']        = '7778';

$games['ut']['name']                = 'Unreal Tournament';
$games['ut']['protocol']            = 'GameSpy';
$games['ut']['queryport']           = '7778';

$games['ut2003']['name']            = 'Unreal Tournament 2003';
$games['ut2003']['protocol']        = 'ut2003';
$games['ut2003']['queryport']       = '7778';

$games['ut2004']['name']            = 'Unreal Tournament 2004';
$games['ut2004']['protocol']        = 'ut2003';
$games['ut2004']['queryport']       = '7778';

$games['v8']['name']                = 'V8 Supercar Challenge';
$games['v8']['protocol']            = 'GameSpy';
$games['v8']['queryport']           = '16700';

$games['vc']['name']                = 'Vietcong';
$games['vc']['protocol']            = 'GameSpy';
$games['vc']['queryport']           = '15425';

?>
