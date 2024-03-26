<!-- https://codepen.io/corenominal/pen/rxOmMJ -->
<html lang="fr">
<?php include("./templates/header.php"); ?>
    <body class='page_test'>
        <div style="width: 100%;">
    <?php
            $color = array();

            $serv_req = $db->query("SELECT DISTINCT id_guild FROM server");
            $user_req = $db->query("SELECT DISTINCT id_auth FROM user");
            $chan_req = $db->query("SELECT DISTINCT channel_id FROM channel");

            while ($servid = $serv_req->fetchArray()) {
                $temp_color = array(mt_rand(40, 102),mt_rand(10, 90),mt_rand(10, 90)) ;
                $color[$servid['id_guild']] = $temp_color;
            }

            while ($userid = $user_req->fetchArray()) {
                $temp_color = array(mt_rand(102, 245),mt_rand(90, 170),mt_rand(90, 170)) ;
                $color[$userid['id_auth']] = $temp_color;
            }

            while ($chanid = $chan_req->fetchArray()) {
                $temp_color = array(mt_rand(102, 245),mt_rand(170, 250),mt_rand(170, 250)) ;
                $color[$chanid['channel_id']] = $temp_color;
            }

        ?>
        </div>
        <div style="width: 99%; max-height: 75%; max-height: 75vh; overflow-y: scroll; margin: 5px; padding:5px; background-color: #555;">
            <table style='width: 100%;'>
                <thead>
                    <tr>
                        <!-- <th colspan="7">Max 400 lignes | table: 'message'</th> -->
                        <th colspan="5">Max 400 lignes | table: 'message'</th>
                    </tr>
                        <tr>
                        <th>id_msg</th>
                        <th>id_guild</th>
                        <th>id_auth</th>
                        <th>channel_id</th>
                        <!-- <th>message</th> -->
                        <!-- <th>contenu</th> -->
                        <th>timestamp</th>

                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $msg_all = $db->query("SELECT * FROM message ORDER BY timestamp DESC LIMIT 400 ");
                        while ($row_msg_all = $msg_all->fetchArray()) {
                            echo"<tr>";
                                $diviseur = 4;
                                $r = $color[$row_msg_all['id_guild']][0];
                                $g = $color[$row_msg_all['id_guild']][1];
                                $b = $color[$row_msg_all['id_guild']][2];
                                $r2 = round($r * $diviseur);
                                $g2 = round($g * $diviseur);
                                $b2 = round($b * $diviseur);
                                $r3 = $color[$row_msg_all['id_auth']][0];
                                $g3 = $color[$row_msg_all['id_auth']][1];
                                $b3 = $color[$row_msg_all['id_auth']][2];
                                $r32 = round($r / $diviseur);
                                $g32 = round($g / $diviseur);
                                $b32 = round($b / $diviseur);
                                $r4 = $color[$row_msg_all['channel_id']][0];
                                $g4 = $color[$row_msg_all['channel_id']][1];
                                $b4 = $color[$row_msg_all['channel_id']][2];
                                $r42 = round($r / $diviseur);
                                $g42 = round($g / $diviseur);
                                $b42 = round($b / $diviseur);

                                    echo "<td style='background:rgb({$r3},{$g3},{$b3});color:rgb({$r32},{$g32},{$b32});'>{$row_msg_all['id_msg']}</td>";
                                    echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_msg_all['id_guild']}</td>";
                                    echo "<td style='background:rgb({$r3},{$g3},{$b3});color:rgb({$r32},{$g32},{$b32});'>{$row_msg_all['id_auth']}</td>";
                                    echo "<td style='background:rgb({$r4},{$g4},{$b4});color:rgb({$r42},{$g42},{$b42});'>{$row_msg_all['channel_id']}</td>";
                                    // echo "<td style='background:rgb({$r3},{$g3},{$b3});color:rgb({$r32},{$g32},{$b32});'>{$row_msg_all['message']}</td>";
                                    // echo "<td style='background:rgb({$r3},{$g3},{$b3});color:rgb({$r32},{$g32},{$b32});'>{$row_msg_all['contenu']}</td>";
                                    echo "<td style='background:rgb({$r3},{$g3},{$b3});color:rgb({$r32},{$g32},{$b32});'>{$row_msg_all['timestamp']}</td>";
                            echo"</tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
        <div style="width: 99%; max-height: 75%; max-height: 75vh; overflow-y: scroll; margin: 5px; padding:5px; background-color: #555;">
            <table style='width: 100%;'>
                <thead>
                    <tr>
                        <th colspan="4">Max 100 lignes | table: 'server'</th>
                    </tr>
                        <tr>
                        <th>id_guild</th>
                        <th>guild_name</th>
                        <th>nb_message</th>
                        <th>timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $serv_all = $db->query("SELECT * FROM server ORDER BY nb_message DESC LIMIT 100");
                        while ($row_serv_all = $serv_all->fetchArray()) {
                            echo"<tr>";
                                $diviseur = 4;
                                $r = $color[$row_serv_all['id_guild']][0];
                                $g = $color[$row_serv_all['id_guild']][1];
                                $b = $color[$row_serv_all['id_guild']][2];
                                $r2 = round($r * $diviseur);
                                $g2 = round($g * $diviseur);
                                $b2 = round($b * $diviseur);

                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_serv_all['id_guild']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_serv_all['guild_name']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_serv_all['nb_message']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_serv_all['timestamp']}</td>";
                            echo"</tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
        <div style="width: 99%; max-height: 75%; max-height: 75vh; overflow-y: scroll; margin: 5px; padding:5px; background-color: #555;">
            <table style='width: 100%;'>
                <thead>
                    <tr>
                        <th colspan="5">Max 100 lignes | table: 'user'</th>
                    </tr>
                        <tr>
                        <th>id_auth</th>
                        <th>auth</th>
                        <th>isBOT</th>
                        <th>nb_message_user</th>
                        <th>timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $user_all = $db->query("SELECT * FROM user ORDER BY nb_message_user DESC LIMIT 100");
                        while ($row_user_all = $user_all->fetchArray()) {
                            echo"<tr>";
                                $diviseur = 4;
                                $r = $color[$row_user_all['id_auth']][0];
                                $g = $color[$row_user_all['id_auth']][1];
                                $b = $color[$row_user_all['id_auth']][2];
                                $r2 = round($r / $diviseur);
                                $g2 = round($g / $diviseur);
                                $b2 = round($b / $diviseur);

                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_user_all['id_auth']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_user_all['auth']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_user_all['isBOT']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_user_all['nb_message_user']}</td>";
                                echo "<td style='background:rgb({$r},{$g},{$b});color:rgb({$r2},{$g2},{$b2});'>{$row_user_all['timestamp']}</td>";
                            echo"</tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
        <div style="width: 99%; max-height: 75%; max-height: 75vh; overflow-y: scroll; margin: 5px; padding:5px; background-color: #555;">
            <table style='width: 100%;'>
                <thead>
                    <tr>
                        <th colspan="4">Max 200 lignes | table: 'channel'</th>
                    </tr>
                        <tr>
                        <th>channel_id</th>
                        <th>channel</th>
                        <th>nb_message_channel</th>
                        <th>timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        $channel_all = $db->query("SELECT * FROM channel ORDER BY nb_message_channel DESC LIMIT 200");
                        while ($row_channel_all = $channel_all->fetchArray()) {
                            echo"<tr>";
                                $diviseur = 4;
                                $r4 = $color[$row_channel_all['channel_id']][0];
                                $g4 = $color[$row_channel_all['channel_id']][1];
                                $b4 = $color[$row_channel_all['channel_id']][2];
                                $r42 = round($r / $diviseur);
                                $g42 = round($g / $diviseur);
                                $b42 = round($b / $diviseur);

                                echo "<td style='background:rgb({$r4},{$g4},{$b4});color:rgb({$r42},{$g42},{$b42});'>{$row_channel_all['channel_id']}</td>";
                                echo "<td style='background:rgb({$r4},{$g4},{$b4});color:rgb({$r42},{$g42},{$b42});'>{$row_channel_all['channel']}</td>";
                                echo "<td style='background:rgb({$r4},{$g4},{$b4});color:rgb({$r42},{$g42},{$b42});'>{$row_channel_all['nb_message_channel']}</td>";
                                echo "<td style='background:rgb({$r4},{$g4},{$b4});color:rgb({$r42},{$g42},{$b42});'>{$row_channel_all['timestamp']}</td>";
                            echo"</tr>";
                        }
                        ?>
                </tbody>
            </table>
        </div>
    </body>
</html>
