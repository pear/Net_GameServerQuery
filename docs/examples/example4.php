<html>
<head>
    <title>Net_GameServerQuery Example 4: A server listing</title>
</head>
<body>
<h1>Net_GameServerQuery Example 4: A server listing</h1>
<p>This example shows how you could use Net_GameServerQuery to show a list of your favorite servers.</p>
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
$template['results'] = $gsq->execute();

// Let the template file handle the display
require 'example4.tpl.php';

?>
</body>
</html>