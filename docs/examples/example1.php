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
//$serv1 = $gsq->addServer('bfvietnam', '203.26.94.170', null, 'rules|status|players|ping');
//$serv2 = $gsq->addServer('halflife', '202.173.159.8', null, 'rules|status|players|ping');
//$serv3 = $gsq->addServer('doom3', '217.123.78.245', null, 'rules|status|players|ping');

// fire up
$result = $gsq->execute(300);

// results
$bm->stop();

print_r($result);

// benchmark
echo 'Took ... ' . $bm->timems() . 'ms';
?>
</pre>