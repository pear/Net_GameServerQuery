<pre>
<?php
include 'Net/GameServerQuery.php';

// load class
$gsq = new Net_GameServerQuery;

// add servers
$gsq->addServer('bfvietnam', '203.26.94.170', null, 'rules|status|players');
$gsq->addServer('halflife', '202.173.159.8', null, 'rules|status|players');

// fire up
$result = $gsq->execute(100);

// results
print_r($result);
?>
</pre>