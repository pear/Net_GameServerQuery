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
//$gsq->addServer('bfvietnam', '203.26.94.170', null, 'rules|status|players');
$gsq->addServer('halflife', '202.173.159.8', null, 'rules|status|players');
//$gsq->addServer('doom3', '217.123.78.245', null, 'rules|status|players');
//$gsq->addServer('quakeworld', '194.109.69.76', null, 'rules|status|players');

// fire up
$result = $gsq->execute(100);

// results
$bm->stop();

print_r($result);

// benchmark
echo 'Took ... ' . $bm->timems() . 'ms';
?>
</pre>