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
$serv3 = $gsq->addServer('halflife', '203.26.94.152', null, 'rules|status|players');

// fire up
$result = $gsq->execute();
$bm->stop();
ksort($result);

// results
foreach ($result as $server => $reply) {
    echo "Server $server\n";
    $i = 0;
    foreach ($reply as $packet) {
        ++$i;
        echo "Packet #$i (" . strlen($packet) . "bytes)\n";
    }
    echo "\n";
}

// benchmark
echo 'Took ... ' . $bm->timems() . 'ms';
?>
</pre>