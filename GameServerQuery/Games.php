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


/*
 * Define a limited subset of normalised data for status queries
 */
$normals[0]                     = array('hostname',     'map',      'numplayers',   'maxplayers',       'password',     'mode');
$normals['Breed']               = array();
$normals['Doom3']               = array('si_name',      'si_map',   'si_numplayers','si_maxplayers',    'si_usepass',   'si_gameType');
$normals['FarCry']              = array();
$normals['Freelancer']          = array();
$normals['GameSpy']             = array('hostname',     'mapname',  'numplayers',   'maxplayers',       false,          'gametype');
$normals['GameSpy04']           = array('hostname',     'map',      'numplayers',   'maxplayers',       'password',     'game_id');
$normals['GhostRecon']          = array();
$normals['HalfLife']            = array('hostname',     'map',      'players',      'max',              'password',     'gamedir');
$normals['HalfLife2']           = array('hostname',     'map',      'numplayers',   'maxplayers',       'password',     'gamedir');
$normals['Hexen2']              = array();
$normals['HomeWorld2']          = array();
$normals['JediKnight']          = array();
$normals['JediKnight2']         = array();
$normals['MultiTheftAuto']      = array();
$normals['QuakeWorld']          = array('hostname',     'map',      'deathmatch',   'maxclients',       false,          false);
$normals['Quake2']              = array();
$normals['Quake3']              = array('hostname',     'mapname',  'clients',      'sv_maxclients',    false,          'gametype');
$normals['RedFaction']          = array();
$normals['RavenShield']         = array();
$normals['Savage']              = array();
$normals['Starsiege']           = array();
$normals['Tribes']              = array();
$normals['UnrealEngine2']       = array('servername',   'mapname',  'playercount',  'maxplayers',       false,          'gametype');


/*
 * Define packets for each protocol
 */
$packets['Breed']['players']                = array();
$packets['Breed']['rules']                  = array();
$packets['Breed']['status']                 = array();

$packets['Doom3']['players']                = array('players',      "\xFF\xFFgetInfo\x00\x00\x00\x00\x00");
$packets['Doom3']['rules']                  = array('getinfo',      "\xFF\xFFgetInfo\x00\x00\x00\x00\x00");
$packets['Doom3']['status']                 = array('getinfo',      "\xFF\xFFgetInfo\x00\x00\x00\x00\x00");

$packets['FarCry']['players']               = array('players',      "\x7F\xFF\xFF\xFFplayers");
$packets['FarCry']['rules']                 = array('rules',        "\x7F\xFF\xFF\xFFrules");
$packets['FarCry']['status']                = array('status',       "\x7F\xFF\xFF\xFFstatus");

$packets['Freelancer']['players']           = array();
$packets['Freelancer']['rules']             = array();
$packets['Freelancer']['status']            = array();

$packets['GameSpy']['players']              = array('players',      "\\players\\");
$packets['GameSpy']['rules']                = array('status',       "\\status\\");
$packets['GameSpy']['status']               = array('info',         "\\info\\");

$packets['GameSpy04']['players']            = array('playerinfo',   "\xFE\xFD\x00NGSQ\x00\xFF\xFF");
$packets['GameSpy04']['rules']              = array('serverinfo',   "\xFE\xFD\x00NGSQ\xFF\x00\x00");
$packets['GameSpy04']['status']             = array('serverinfo',   "\xFE\xFD\x00NGSQ\xFF\x00\x00");

$packets['GhostRecon']['players']           = array();
$packets['GhostRecon']['rules']             = array();
$packets['GhostRecon']['status']            = array();

$packets['HalfLife']['players']             = array('players',      "\xFF\xFF\xFF\xFFplayers");
$packets['HalfLife']['rules']               = array('rules',        "\xFF\xFF\xFF\xFFrules");
$packets['HalfLife']['status']              = array('infostring',   "\xFF\xFF\xFF\xFFinfostring");

$packets['HalfLife2']['players']            = array('players',      "\xFF\xFF\xFF\xFF\x55");
$packets['HalfLife2']['rules']              = array('rules',        "\xFF\xFF\xFF\xFF\x56");
$packets['HalfLife2']['status']             = array('details',      "\xFF\xFF\xFF\xFF\x54");

$packets['Hexen2']['players']               = array();
$packets['Hexen2']['rules']                 = array();
$packets['Hexen2']['status']                = array();

$packets['HomeWorld2']['players']           = array();
$packets['HomeWorld2']['rules']             = array();
$packets['HomeWorld2']['status']            = array();

$packets['JediKnight']['players']           = array();
$packets['JediKnight']['rules']             = array();
$packets['JediKnight']['status']            = array();

$packets['JediKnight2']['players']          = array();
$packets['JediKnight2']['rules']            = array();
$packets['JediKnight2']['status']           = array();

$packets['MultiTheftAuto']['players']       = array();
$packets['MultiTheftAuto']['rules']         = array();
$packets['MultiTheftAuto']['status']        = array();

$packets['QuakeWorld']['players']           = array('players',      "\xFF\xFF\xFF\xFFstatus");
$packets['QuakeWorld']['rules']             = array('status',       "\xFF\xFF\xFF\xFFstatus");
$packets['QuakeWorld']['status']            = array('status',       "\xFF\xFF\xFF\xFFstatus");

$packets['Quake2']['players']               = array('status',       "\xFF\xFF\xFF\xFFstatus");
$packets['Quake2']['rules']                 = array('status',       "\xFF\xFF\xFF\xFFstatus");
$packets['Quake2']['status']                = array('info',         "\xFF\xFF\xFF\xFFinfo");

$packets['Quake3']['players']               = array('players',      "\xFF\xFF\xFF\xFFgetstatus");
$packets['Quake3']['rules']                 = array('rules',        "\xFF\xFF\xFF\xFFgetstatus");
$packets['Quake3']['status']                = array('info',         "\xFF\xFF\xFF\xFFgetinfo");

$packets['RedFaction']['players']           = array();
$packets['RedFaction']['rules']             = array();
$packets['RedFaction']['status']            = array();

$packets['RavenShield']['players']          = array();
$packets['RavenShield']['rules']            = array();
$packets['RavenShield']['status']           = array();

$packets['Savage']['players']               = array();
$packets['Savage']['rules']                 = array();
$packets['Savage']['status']                = array();

$packets['Starsiege']['players']            = array();
$packets['Starsiege']['rules']              = array();
$packets['Starsiege']['status']             = array();

$packets['Tribes']['players']               = array();
$packets['Tribes']['rules']                 = array();
$packets['Tribes']['status']                = array();

$packets['UnrealEngine2']['players']        = array('players',      "\x78\x00\x00\x00\x02");
$packets['UnrealEngine2']['rules']          = array('rules',        "\x78\x00\x00\x00\x01");
$packets['UnrealEngine2']['status']         = array('status',       "\x78\x00\x00\x00\x00");


/*
 * Define games and associated protocols
 */
$games['aarmy']['title']            = 'America\'s Army: Operations';
$games['aarmy']['protocol']         = 'GameSpy';
$games['aarmy']['queryport']        = '1717';

$games['avp2']['title']             = 'Alien vs Predator 2';
$games['avp2']['protocol']          = 'GameSpy';
$games['avp2']['queryport']         = '27888';

$games['bf1942']['title']           = 'Battlefield 1942';
$games['bf1942']['protocol']        = 'GameSpy';
$games['bf1942']['queryport']       = '23000';

$games['bfvietnam']['title']        = 'BattleField: Vietnam';
$games['bfvietnam']['protocol']     = 'GameSpy04';
$games['bfvietnam']['queryport']    = '23000';

$games['breed']['title']            = 'Breed';
$games['breed']['protocol']         = 'Breed';
$games['breed']['queryport']        = '7649';

$games['callofduty']['title']       = 'Call of Duty';
$games['callofduty']['protocol']    = 'Quake3';
$games['callofduty']['queryport']   = '28960';

$games['ccrene']['title']           = 'Command & Conquer: Renegade';
$games['ccrene']['protocol']        = 'GameSpy';
$games['ccrene']['queryport']       = '25300';

$games['contjack']['title']         = 'Contract J.A.C.K.';
$games['contjack']['protocol']      = 'GameSpy';
$games['contjack']['queryport']     = '27888';

$games['daikatana']['title']        = 'Daikatana';
$games['daikatana']['protocol']     = 'GameSpy';
$games['daikatana']['queryport']    = '27992';

$games['deusex']['title']           = 'Deus Ex';
$games['deusex']['protocol']        = 'GameSpy';
$games['deusex']['queryport']       = '7791';

$games['devastat']['title']         = 'Devastation';
$games['devastat']['protocol']      = 'UnrealTournament03';
$games['devastat']['queryport']     = '7778';

$games['doom3']['title']            = 'Doom 3';
$games['doom3']['protocol']         = 'Doom3';
$games['doom3']['queryport']        = '27666';

$games['drakan']['title']           = 'Drakan: Order of the Flame';
$games['drakan']['protocol']        = 'GameSpy';
$games['drakan']['queryport']       = '27046';

$games['farcry']['title']           = 'FarCry';
$games['farcry']['protocol']        = 'FarCry';
$games['farcry']['queryport']       = '49001';

$games['freelancer']['title']       = 'Freelancer';
$games['freelancer']['protocol']    = 'Freelancer';
$games['freelancer']['queryport']   = '2302';

$games['giants']['title']           = 'Giants: Citizen Kabuto';
$games['giants']['protocol']        = 'GameSpy';
$games['giants']['queryport']       = '8911';

$games['globalops']['title']        = 'Global Operations';
$games['globalops']['protocol']     = 'GameSpy';
$games['globalops']['queryport']    = '28672';

$games['gore']['title']             = 'Gore';
$games['gore']['protocol']          = 'GameSpy';
$games['gore']['queryport']         = '27778';

$games['ghostrecon']['title']       = 'Ghost Recon';
$games['ghostrecon']['protocol']    = 'GhostRecon';
$games['ghostrecon']['queryport']   = '2348';

$games['halo']['title']             = 'Halo: Combat Evolved';
$games['halo']['protocol']          = 'GameSpy';
$games['halo']['queryport']         = '2302';

$games['halflife']['title']         = 'Half-Life';
$games['halflife']['protocol']      = 'HalfLife';
$games['halflife']['queryport']     = '27015';

$games['halflife2']['title']        = 'Half-Life: Source';
$games['halflife2']['protocol']     = 'HalfLife';
$games['halflife2']['queryport']    = '27015';

$games['homeworld2']['title']       = 'Homeworld 2';
$games['homeworld2']['protocol']    = 'HomeWorld2';
$games['homeworld2']['queryport']   = '6500';

$games['hexen2']['title']           = 'Hexen 2';
$games['hexen2']['protocol']        = 'Hexen2';
$games['hexen2']['queryport']       = '26900';

$games['igi2']['title']             = 'IGI 2';
$games['igi2']['protocol']          = 'GameSpy';
$games['igi2']['queryport']         = '26001';

$games['jediknight2']['title']      = 'Jedi Knight 2: Jedi Outcast';
$games['jediknight2']['protocol']   = 'JediKnight2';
$games['jediknight2']['queryport']  = '28070';

$games['jediknight']['title']       = 'Jedi Knight: Jedi Academy';
$games['jediknight']['protocol']    = 'JediKnight';
$games['jediknight']['queryport']   = '29070';

$games['kingpin']['title']          = 'Kingpin: Life of Crime';
$games['kingpin']['protocol']       = 'Quake3';
$games['kingpin']['queryport']      = '31510';

$games['mohaa']['title']            = 'Medal of Honor';
$games['mohaa']['protocol']         = 'Quake3';
$games['mohaa']['queryport']        = '12300';

$games['mta']['title']              = 'Multi Theft Auto: Vice City';
$games['mta']['protocol']           = 'MultiTheftAuto';
$games['mta']['queryport']          = '2126';

$games['nightfire']['title']        = 'James Bond: Nightfire';
$games['nightfire']['protocol']     = 'HalfLife';
$games['nightfire']['queryport']    = '27015';

$games['nitro']['title']            = 'Nitro Family';
$games['nitro']['protocol']         = 'GameSpy';
$games['nitro']['queryport']        = '25601';

$games['nwn']['title']              = 'Neverwinter Nights';
$games['nwn']['protocol']           = 'GameSpy';
$games['nwn']['queryport']          = '5121';

$games['nolf']['title']             = 'No One Lives Forever';
$games['nolf']['protocol']          = 'GameSpy';
$games['nolf']['queryport']         = '27888';

$games['nolf2']['title']            = 'No One Lives Forever 2';
$games['nolf2']['protocol']         = 'GameSpy';
$games['nolf2']['queryport']        = '27890';

$games['opflash']['title']          = 'Operation Flashpoint';
$games['opflash']['protocol']       = 'GameSpy';
$games['opflash']['queryport']      = '6073';

$games['postal2']['title']          = 'Postal 2';
$games['postal2']['protocol']       = 'GameSpy';
$games['postal2']['queryport']      = '7778';

$games['painkiller']['title']       = 'Pain Killer';
$games['painkiller']['protocol']    = 'GameSpy04';
$games['painkiller']['queryport']   = '3455';

$games['quakeworld']['title']       = 'QuakeWorld';
$games['quakeworld']['protocol']    = 'QuakeWorld';
$games['quakeworld']['queryport']   = '27500';

$games['quake2']['title']           = 'Quake 2';
$games['quake2']['protocol']        = 'Quake2';
$games['quake2']['queryport']       = '27910';

$games['quake3']['title']           = 'Quake 3 Arena';
$games['quake3']['protocol']        = 'Quake3';
$games['quake3']['queryport']       = '27960';

$games['redfaction']['title']       = 'Red Faction';
$games['redfaction']['protocol']    = 'RedFaction';
$games['redfaction']['queryport']   = '7755';

$games['riseofnat']['title']        = 'Rise of nations';
$games['riseofnat']['protocol']     = 'GameSpy';
$games['riseofnat']['queryport']    = '6501';

$games['rainbow6']['title']         = 'Rainbow Six';
$games['rainbow6']['protocol']      = 'GameSpy';
$games['rainbow6']['queryport']     = '2346';

$games['rtcw']['title']             = 'Return to Castle Wolfenstein';
$games['rtcw']['protocol']          = 'Quake3';
$games['rtcw']['queryport']         = '27960';

$games['roguespear']['title']       = 'Rogue Spear';
$games['roguespear']['protocol']    = 'GameSpy';
$games['roguespear']['queryport']   = '2346';

$games['rune']['title']             = 'Rune';
$games['rune']['protocol']          = 'GameSpy';
$games['rune']['queryport']         = '7778';

$games['ravenshield']['title']      = 'RavenShield';
$games['ravenshield']['protocol']   = 'RavenShield';
$games['ravenshield']['queryport']  = '8777';

$games['savage']['title']           = 'Savage: The Battle For Newerth';
$games['savage']['protocol']        = 'Savage';
$games['savage']['queryport']       = '11235';

$games['shogo']['title']            = 'Shogo: Armored Division';
$games['shogo']['protocol']         = 'GameSpy';
$games['shogo']['queryport']        = '27888';

$games['sin']['title']              = 'SIN';
$games['sin']['protocol']           = 'Quake3';
$games['sin']['queryport']          = '22450';

$games['sof']['title']              = 'Soldier of Fortune';
$games['sof']['protocol']           = 'Quake3';
$games['sof']['queryport']          = '28910';

$games['sof2']['title']             = 'Soldier of Fortune 2: Double Helix';
$games['sof2']['protocol']          = 'Quake3';
$games['sof2']['queryport']         = '20100';

$games['serioussam']['title']       = 'Serious Sam';
$games['serioussam']['protocol']    = 'GameSpy';
$games['serioussam']['queryport']   = '25601';

$games['serioussam2']['title']      = 'Serious Sam: The Second Encounter';
$games['serioussam2']['protocol']   = 'GameSpy';
$games['serioussam2']['queryport']  = '25601';

$games['starsiege']['title']        = 'Starsiege';
$games['starsiege']['protocol']     = 'Starsiege';
$games['starsiege']['queryport']    = '29001';

$games['startrekv']['title']        = 'Star Trek Voyager: Elite Force';
$games['startrekv']['protocol']     = 'Quake3';
$games['startrekv']['queryport']    = '27960';

$games['startrekv2']['title']       = 'Star Trek Voyager: Elite Force 2';
$games['startrekv2']['protocol']    = 'Quake3';
$games['startrekv2']['queryport']   = '29253';

$games['tribes']['title']           = 'Starsiege: Tribes';
$games['tribes']['protocol']        = 'Tribes';
$games['tribes']['queryport']       = '28001';

$games['tactops']['title']          = 'Tactical Operations';
$games['tactops']['protocol']       = 'GameSpy';
$games['tactops']['queryport']      = '7778';

$games['teamfactor']['title']       = 'Team Factor';
$games['teamfactor']['protocol']    = 'GameSpy';
$games['teamfactor']['queryport']   = '57778';

$games['tonyhawk']['title']         = 'Tony Hawk\'s Pro Skater';
$games['tonyhawk']['protocol']      = 'GameSpy';
$games['tonyhawk']['queryport']     = '6500';

$games['tribes2']['title']          = 'Tribes 2';
$games['tribes2']['protocol']       = 'GameSpy';
$games['tribes2']['queryport']      = '28001';

$games['tron2']['title']            = 'Tron 2';
$games['tron2']['protocol']         = 'GameSpy';
$games['tron2']['queryport']        = '27888';

$games['unreal']['title']           = 'Unreal';
$games['unreal']['protocol']        = 'GameSpy';
$games['unreal']['queryport']       = '7778';

$games['unreal2xmp']['title']       = 'Unreal 2 XMP';
$games['unreal2xmp']['protocol']    = 'UnrealEngine2';
$games['unreal2xmp']['queryport']   = '7778';

$games['unrealt']['title']          = 'Unreal Tournament';
$games['unrealt']['protocol']       = 'GameSpy';
$games['unrealt']['queryport']      = '7778';

$games['ut2003']['title']           = 'Unreal Tournament 2003';
$games['ut2003']['protocol']        = 'UnrealEngine2';
$games['ut2003']['queryport']       = '7778';

$games['ut2004']['title']           = 'Unreal Tournament 2004';
$games['ut2004']['protocol']        = 'UnrealEngine2';
$games['ut2004']['queryport']       = '7778';

$games['v8super']['title']          = 'V8 Supercar Challenge';
$games['v8super']['protocol']       = 'GameSpy';
$games['v8super']['queryport']      = '16700';

$games['vietcong']['title']         = 'Vietcong';
$games['vietcong']['protocol']      = 'GameSpy';
$games['vietcong']['queryport']     = '15425';

?>