<html>
<head>
    <title>Net_GameServerQuery Example 5: A more advanced server listing</title>
</head>
<body>
<h1>Net_GameServerQuery Example 5: A more advanced server listing</h1>
<p>This example, like Example 4, shows how you could use Net_GameServerQuery to show a list of your favorite servers.</p>
<p>However, in this example, we go a little further showing the rules and players in each server.</p>
<p>We also sort the players by kill, and the server list by the game.</p>
<?php
require_once 'Net/GameServerQuery.php';

// Add servers
$gsq = new Net_GameServerQuery;
$gsq->addServer('callofduty',   'games01.syd.optusnet.com.au',  '24020');
$gsq->addServer('callofduty',   'games01.syd.optusnet.com.au',  '24000');
$gsq->addServer('halflife',     'games07.syd.optusnet.com.au',  '24000');
$gsq->addServer('halflife',     'games07.syd.optusnet.com.au',  '24010');
$gsq->addServer('halflife',     'games07.syd.optusnet.com.au',  '24020');
$gsq->addServer('halflife',     'games07.syd.optusnet.com.au',  '24030');
$gsq->addServer('halflife',     'games12.syd.optusnet.com.au',  '24020');
$gsq->addServer('halflife',     'games06.syd.optusnet.com.au',  '24280');
$gsq->addServer('quake3',       'games02.syd.optusnet.com.au',  '22020');
$gsq->addServer('quake3',       'games02.syd.optusnet.com.au',  '22070');
$gsq->addServer('quake3',       'games02.syd.optusnet.com.au',  '22000');
$gsq->addServer('quake3',       'games02.syd.optusnet.com.au',  '22010');

// Execute
$results = $gsq->execute();

// Sort the data by the game
foreach ($results as $result) {
    $data[$result['__game']][] = $result;
}
$template['data'] = $data;

// Check if we need to make additional queries
if (isset($_GET['view'])) {
    $serverkey = $gsq->addServer($_GET['game'], $_GET['view'], $_GET['port'], 'players|rules');
    $result = $gsq->execute();
    $template['moreinfo'] = $result[$serverkey];
}

// Let the template file handle the display
require 'example5.tpl.php';

?>
</body>
</html>