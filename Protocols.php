<?php
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