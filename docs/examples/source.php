<html>
<head>
	<title>Net_GameServerQuery Source Viewer</title>
	<style>
		h1 { font-size: 12pt; }
		body { font-size: 9pt; font-family: Verdana, Arial, sans-serif; }
	</style>
</head>

<body>

<h1>Net_GameServerQuery Source Viewer</h1>

PEAR<br>
---- Net<br>
---- <a href="?class">GameServerQuery.php</a><br>
---- ---- GameServerQuery<br>
---- ---- <a href="?games">Games.xml</a><br>
---- ---- ---- Objects<br>
---- ---- ---- <a href="?class_error">Error.php</a><br>
---- ---- ---- <a href="?class_prot">Protocol.php</a><br>
---- ---- ---- <a href="?class_socket">Socket.php</a><br>
---- ---- ---- Protocols<br>
---- ---- ---- <a href="?prot_halflife">Net/GameServerQuery/Protocols/halflife.php</a><br>
<br>
Implementation<br>
<a href="?query">query.php</a><br>
<br>
<hr>
<br>

<?php
switch ($_SERVER['QUERY_STRING']):
	case 'query':
		show_source('query.php');
		break;
		
	case 'class':
		show_source('Net/GameServerQuery.php');
		break;

	case 'games':
		show_source('Net/GameServerQuery/Games.xml');
		break;

	case 'class_error':
		show_source('Net/GameServerQuery/Objects/Error.php');
		break;

	case 'class_prot':
		show_source('Net/GameServerQuery/Objects/Protocol.php');
		break;

	case 'class_socket':
		show_source('Net/GameServerQuery/Objects/Socket.php');
		break;

	case 'prot_halflife':
		show_source('Net/GameServerQuery/Protocols/halflife.php');
		break;



endswitch;
?>

</body>
</html>