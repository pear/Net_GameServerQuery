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
$serv1 = $gsq->addServer('painkiller', '203.26.94.155', 3455, 'rules');
//$serv2 = $gsq->addServer('halflife', '202.173.159.8', null, 'rules');
//$serv3 = $gsq->addServer('halflife', '203.26.94.152', null, 'status|rules');

// fire up
$result = $gsq->execute(100);

// results
$bm->stop();
print_r($result);

// benchmark
echo 'Took ... ' . $bm->timems() . 'ms';

//xdebug_dump_function_trace()
?>
</pre>