<pre>
<?php
include 'GameServerQuery.php';
include 'Benchmark.php';
include 'function.hexdump.php';

// load class
$gsq = new Net_GameServerQuery;
$bm = new Benchmark;
$bm->start();

// add servers
$serv1 = $gsq->addServer('halflife', '202.173.159.7', null, 'players|status|rules');
$serv2 = $gsq->addServer('halflife', '202.173.159.8', null, 'rules|ping');
$serv3 = $gsq->addServer('halflife', '203.26.94.152', null, 'status|rules');

// fire up
$result = $gsq->execute(100);

// results
$bm->stop();
print_r($result);

// benchmark
echo 'Took ... ' . $bm->timems() . 'ms';
?>
</pre>