<html>
<head>
    <title>Net_GameServerQuery Example 3: Accessing communication classes</title>
</head>
<body>
<h1>Net_GameServerQuery Example 3: Accessing communication classes</h1>
<p>This example shows how the communication class manually with Net_GameServerQuery.</p>
<p>We ask a Half-Life server for its status.</p>
<pre>
<?php
require_once 'Net/GameServerQuery.php';

// Get the packet to send
$cfg = new Net_GameServerQuery_Config;
list($packetname, $packet) = $cfg->getPacket('HalfLife', 'status');

// Create array of server information
$serverdata = array(
    0 => array(
        'packet'    => $packet,
        'addr'      => '202.12.147.111',
        'port'      => 27045,
        ));

// Query
$gsq_comm = new Net_GameServerQuery_Communicate;
$results = $gsq_comm->query($serverdata, 200);

?>
</pre>
</body>
</html>