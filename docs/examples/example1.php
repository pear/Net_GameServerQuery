<html>
<head>
    <title>Net_GSQ Example 1: Basics</title>
</head>
<body>
<p>This example shows the basic usage of Net_GameServerQuery.</p>
<p>We retrieve rules, status and players from two servers.</p>
<pre>
<?php
require 'Net/GameServerQuery.php';

// load class
$gsq = new Net_GameServerQuery;

// add servers
$gsq->addServer('bfvietnam', '203.26.94.170', null, 'rules|status|players');
$gsq->addServer('halflife', '202.173.159.8', null, 'rules|status|players');

// execute
$result = $gsq->execute(100);

// results
print_r($result);
?>
</pre>
</body>
</html>