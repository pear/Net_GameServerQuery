<html>
<head>
    <title>Net_GSQ Example 2: Using protocol parsers</title>
</head>
<body>
<p>This example shows how the protocol parsers can be accessed with Net_GameServerQuery.</p>
<p>We convert a rules, status and players sample request into parsed data.</p>
<p>This example requires <em>examplepackets.php</em> to be in the same directory.</p>
<pre>
<?php
require 'Net/GameServerQuery.php';

// Raw packets from the server
require_once 'examplepackets.php';

// Load HalfLife protocol
$parser = Net_GameServerQuery_Process::factory('HalfLife');

// Get rules from the packet
$rules = $parser->parse('rules', $packets['HalfLife']['rules']);

// Results
print_r($rules);

?>
</pre>
</body>
</html>