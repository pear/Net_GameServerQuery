<?php
/*
 * This is part of Example 5.
 *
 * This file handles the display of information gathered in example5.php
 */
?>
<table border="1">
    <tr>
        <th>Server Name</th>
        <th>Map</th>
        <th>Players</th>
        <th>View</th>
    </tr>
    <?php
    $data = $template['data'];
    foreach ($data as $game):
        ?>
        <tr>
            <th colspan="4"><?php echo $game[0]['__gametitle']; ?></th>
        </tr>
        <?php
        foreach ($game as $result):
            $status = $result['status'];
            if ($status === false):
                ?>
                <tr>
                    <td colspan="4">Server did not reply to request</td>
                </tr>
                <?php
            else:
                ?>
                <tr>
                    <td><?php echo $status['hostname'];?></td>
                    <td><?php echo $status['map'];?></td>
                    <td><?php echo (int) $status['numplayers'];?> / <?php echo (int) $status['maxplayers'];?></td>
                    <td><a href="<?php
                        $url = $_SERVER['SCRIPT_NAME'] . '?view=' . $result['__addr'] .
                        '&port=' . $result['__port'] . '&game=' . $result['__game'];
                        echo $url;?>">View</a></td>
                </tr>
                <?php
            endif;
        endforeach;
    endforeach;
    ?>
</table>

<?php
if ($template['moreinfo']):
    ?>
    <h2>View Server</h2>
    <h3>Players</h3>
    <table border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th>Nick</th>
            <th>Score</th>
        </tr>
        <?php
        $players = $template['moreinfo']['players'];
        
        // Sort the players
        $score = array();
        foreach ($players as $player) {
            $score[] = $player['score'];
        }
        array_multisort($score, SORT_DESC, SORT_NUMERIC, $players);
        
        // List the info
        foreach ($players as $player):
            ?>
            <tr><td><?php echo $player['name'];?></td><td><?php echo $player['score'];?></td></tr>
            <?php
        endforeach;
        ?>
    </table>

    <h3>Rules</h3>
    <table border="1" cellpadding="0" cellspacing="0">
        <tr>
            <th>Rule</th>
            <th>Value</th>
        </tr>
        <?php
        $rules = $template['moreinfo']['rules'];
        foreach ($rules as $rule => $value):
            ?>
            <tr><td><?php echo $rule;?></td><td><?php echo $value;?></td></tr>
            <?php
        endforeach;
        ?>
    </table>
    <?php
endif;

?>