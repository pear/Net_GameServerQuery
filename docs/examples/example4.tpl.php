<?php
/*
 * This is part of Example 5.
 *
 * This file handles the display of information gathered in example5.php
 */
?>
<table border="1">
    <tr>
        <th>Game</th>
        <th>Server Name</th>
        <th>Map</th>
        <th>Players</th>
    </tr>
    <?php
    $results = $template['results'];
    foreach ($results as $result):
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
                <td><?php echo $result['meta']['gametitle'];?></td>
                <td><?php echo $status['hostname'];?></td>
                <td><?php echo $status['map'];?></td>
                <td><?php echo (int) $status['numplayers'];?> / <?php echo (int) $status['maxplayers'];?></td>
            </tr>
            <?php
        endif;
    endforeach;
    ?>
</table>
</body>
</html>