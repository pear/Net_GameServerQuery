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
$serv1 = $gsq->addServer('halflife', '202.173.159.7', null, 'players');
$serv2 = $gsq->addServer('halflife', '202.173.159.8', null, 'status');
$serv3 = $gsq->addServer('halflife', '203.26.94.152', null, 'rules');

// fire up
$result = $gsq->execute(45);

// results
$bm->stop();
echo $result[$serv1]['players'];
echo "\n\n";
echo $result[$serv2]['status'];
echo "\n\n";
echo $result[$serv3]['rules'];

// benchmark
echo 'Took ... ' . $bm->timems() . 'ms';
?>
</pre>