<?php
/**
* This is a simple example illustrating the use of Net_GameServerQuery
*/
require_once ('Net/GameServerQuery.php');

// Set $ip, $port, $game
$ip = $_GET['server'];
$game = $_GET['game'];

// Dirty function to display our result data
function result_print ($array)
{
	if (is_array($array))
	{
		echo "<table cellpadding=2 cellspacing=0 border=1 width=500>";

		foreach ($array as $key => $value)
		{
			echo "<tr>";
			echo "<td width='200px'>$key:</td>";
			echo "<td>";

			if (is_array($value))
			{
				foreach ($value as $k => $v)
				{
					echo "$k: $v<br>";
				}
			}
			else {
				echo $value; }

			echo "</td>";
			echo "</tr>";
		}

		echo "</table>";
	}
	
	else {
		echo var_dump($array); };
}
?>
<html>
<head>
	<title>Net_GameServerQuery Result Viewer</title>
	<style>
		h2 { font-size: 10pt; margin: 15px 0 2px 0; }
		h1 { font-size: 12pt; }
		body { font-size: 9pt; font-family: Verdana, Arial, sans-serif; }
		td { font-size: 9pt; }
		ul { margin: 7px 0 7px 10px; padding: 0; list-style-type: none; }
		li { margin-bottom: 4px; padding: 0; }
		td { font-size: 7pt; }
	</style>
</head>

<body>

<h1>Net_GameServerQuery Result Viewer</h1>
<p>We're querying <u><?php echo $ip;?></u> using the <u><?php echo $game?></u> protocol.</p>

<?php
// Load the class
$query = new Net_GameServerQuery;

// Query a server
$info = $query->query($ip, null, $game);

// Start the timer
$start = round(Net_GameServerQuery::microtime_str() * 1000);

// Check we didn't hit an error
if (!PEAR::isError($info))
{
	// Status
	echo "\n\n<h2>Status</h2>";
	result_print($info->status());

	// Players
	echo "\n\n<h2>Players</h2>";
	//result_print($info->players());

	// Rules
	echo "\n\n<h2>Rules</h2>";
	//result_print($info->rules());

	// Ping
	echo "\n\n<h2>Ping</h2>";
	//echo $info->ping() . 'ms';

}

else {
	$info->printError();
}


// End the timer
$stop = round(Net_GameServerQuery::microtime_str() * 1000);

// Display Total Time
echo "\n\n<h2>Total time taken</h2>";
echo $stop - $start . 'ms';
?>

</body>
</html>