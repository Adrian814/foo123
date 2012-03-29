<?php session_start(); ?>

<html>
    <head>
        <?php
            //include "ini_set.inc.php";
            include "design.class.php";
            include "user.class.php";

            $user = new userclass();
            $user->checkCookie();

            $design = new designclass();
            $design->createHeader("Demo Kalender", "css/web_tech.css");


        ?>

    </head>
    <body>
        <div id="all">

            <?php

                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content"> <?php
    $EventId = $_GET['EventId'];
        
        
        $user->getDB();


//   
//        $mysql = mysql_select_db('dev.ipirate.ch');
//        //$mysql = mysql_select_db('phpmyadmin');
//
//        if(!$mysql) {
//            echo 'Cannot select database.';
//            exit;
//        }

//checks the user rights
if (isset($_SESSION["nick"])) {
    if ($_SESSION["nick"] == "Admin") {
        if (isset($_POST['DeleteComment'])) {
            $CommentId = $_POST['DeleteComment'];
            mysql_query("DELETE FROM `Comments` WHERE CommentId = \"$CommentId\"");
        }
    }
}
        // reads the informations of an event from the database and fills a html table
        $query = "SELECT * FROM `Events` WHERE EventId = $EventId";
        $result = mysql_query($query);
        if ($result) {
            $row = mysql_fetch_array($result);
            
            echo "<h2>".$row['Title']."</h2>";
            echo "
                <table border='0' cellpadding ='5' width='60%'>
                    <colgroup>
                        <col width='10%'>
                        <col width='80%'>
                    </colgroup>
                    <tr>
                        <td style='font-weight:bold'>Created by</td>";
                        if(isset($_SESSION["nick"]))
                            echo "<td><a href='profile.php?nick=".$row['Creator']."' class='nav'>".$row['Creator']."</a></td>";
                        else
                            echo "<td>".$row['Creator']."</td>";
            echo "  </tr>
                    <tr>
                        <td style='font-weight:bold'>Points</td>
                        <td>".$row['Points']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Supporter</td>
                        <td>".$row['Supporter']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Opponents</td>
                        <td>".$row['Opponents']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Participants</td>
                        <td>".$row['Participants']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Date</td>
                        <td>" .date('d.m.o', strtotime($row['Date'])). "</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Time</td>
                        <td>".date('H:i', strtotime($row['Time']))."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Type</td>
                        <td>".$row['Type']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Location</td>
                        <td>".$row['Location']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Organizer</td>
                        <td>".$row['Organizer']."</td>
                    </tr>
                    <tr>
                        <td style='font-weight:bold'>Issue</td>
                        <td>".$row['Issue']."</td>
                    </tr>
                    <tr>
                        <td valign='top' style='font-weight:bold'>Description</td>
                        <td style='text-align:justify;'>".$row['Description']."</td>
                </table>";
        }

        echo "<hr>";
        
        // makes a list of participants
        echo "<h3><img src='./pictures/Participant.jpg'> Participants:</h3>";
        $query = 'SELECT * FROM Participation WHERE EventId = "' . $EventId . '"';
        $result = mysql_query($query);
        $anz = mysql_num_rows($result);
        if ($anz == 0) {
            echo "<font color='blue'>There are no participants for this event at the time!</font><br><br>";
        }
        else {
            echo "<ul>";
            for ($i=1; $i <= $anz; $i++) {
                $row = mysql_fetch_array($result);
            if(isset($_SESSION["nick"]))
                echo "<li><a href='profile.php?nick=".$row['Participant']."' class='nav'>".$row['Participant']."</a></li>";
            else
                echo "<li>".$row['Participant']."</li>";
            echo "</ul>";
            }
        }
        echo "<hr>";

        // makes a list of comments and a comment field for new comments for logged in users
        echo "<h3><img src='./pictures/Bubble.jpg'> Comments:</h3>";

        if (isset($_POST['formaction'])) {
            if (empty($_POST['Content']))
                   echo "<font color='red'>Please fill the comment-field!</font><br><br>";
            else {
                $query = "INSERT INTO Comments (EventId, Date, Creator, Content)" .
                         "VALUES (\"".$EventId."\","
                                 ."NOW(),"
                                 ."\"".$_SESSION["nick"]."\","
                                 ."\"".$_POST['Content']."\");";
                mysql_query($query);
            }
        }
        
        $query = "SELECT * FROM `Comments` WHERE EventId=$EventId";
        $result = mysql_query($query);
        $num_results = mysql_num_rows($result);
        if ($result) {
            if (!isset($_SESSION["nick"])) {
                if ($num_results == 0) {
                    echo "<font color='blue'>There are no comments for this event at the time!</font><br><br>";
                }
                else {
                    for ($i=0; $i < $num_results; $i++) {
                        $row = mysql_fetch_array($result);
                        echo "
                            <table cellpadding ='5' width='80%'>
                                <tr>
                                    <td style='font-weight:bold'>On ".date('d.m.o', strtotime($row['Date']))." by ".$row['Creator']."</td>
                                </tr>
                                <tr>
                                    <td colspan='2' style='text-align:justify;'>".$row['Content']."</td>
                                </tr>
                            </table>
                        ";
                    }
                }
                echo "<br><font color='blue'>You have to be logged in to comment!</font><br><br>";
            }
            else {
                if ("Admin" != $_SESSION["nick"]) {
                    if ($num_results == 0) {
                        echo "<font color='blue'>There are no comments for this event at the time!</font><br><br>";
                    }
                    else {
                        for ($i=0; $i < $num_results; $i++) {
                            $row = mysql_fetch_array($result);
                            echo "
                                <table cellpadding ='5' width='80%'>
                                    <tr>
                                        <td style='font-weight:bold'>On ".date('d.m.o', strtotime($row['Date']))." by <a href='profile.php?nick=".$row['Creator']."' class='nav'>".$row['Creator']."</a>:"."</td>
                                    </tr>
                                    <tr>
                                        <td colspan='2' style='text-align:justify;'>".$row['Content']."</td>
                                    </tr>
                                </table>
                            ";
                        }
                    }
                    echo "
                        <br><br>
                        Enter a comment, if you like!<br><br>
                        <form action='EventProfile.php?EventId=$EventId' method='post'>
                            <textarea name='Content' cols='30' rows='5'></textarea>
                            <br>
                            <input type='submit' name='formaction' value='Submit'>
                            <input type='reset' value='Reset'><br><br>
                        </form>
                    ";
                    }
                else {
                    if ($num_results == 0) {
                        echo "<font color='blue'>There are no comments for this event at the time!</font><br><br>";
                    }
                    else {
                        for ($i=0; $i < $num_results; $i++) {
                            $row = mysql_fetch_array($result);
                            echo "
                                <form action='EventProfile.php?EventId=$EventId' method='post'>
                                    <table cellpadding ='5' width='80%'>
                                        <tr>
                                            <td style='font-weight:bold'>On ".date('d.m.o', strtotime($row['Date']))." by <a href='profile.php?nick=".$row['Creator']."' class='nav'>".$row['Creator']."</a>:"."</td>
                                        </tr>
                                        <tr>
                                            <td colspan='2' style='text-align:justify;'>".$row['Content']."</td>
                                        </tr>
                                    </table>
                                    <button name='DeleteComment' type='submit' value='".$row['CommentId']."'>Delete Comment</button><br>
                                </form>
                            ";  
                        }
                    }
                    echo "
                        <br><br>
                        Enter a comment, if you like!<br><br>
                        <form action='EventProfile.php?EventId=$EventId' method='post'>
                        <textarea name='Content' cols='30' rows='5'></textarea>
                        <br>
                        <input type='submit' name='formaction' value='Submit'>
                        <input type='reset' value='Reset'><br><br>
                    </form>
                    ";
                }
            }
        }
        
        // delete & report event button, checks rights
        if(isset($_SESSION["nick"])) {
            if($_SESSION["nick"] == "Admin") {
                echo"
                    <form action='index.php' method='post'>
                        <button name='DeleteEvent' type='submit' value='$EventId'>Delete Event</button><br><br>
                    </form>";
            }
            else
                echo "<input type='button' onclick='window.location.href=\"reportEvent.php?id=$EventId\"' value='Report Event' name='report' /><br><br>";
        }
        else
            echo "<input type='button' onclick='window.location.href=\"reportEvent.php?id=$EventId\"' value='Report Event' name='report' /><br><br>";
               
    ?>
                </div>
                <div id="calendar">
                    <?php

                        include "cal.php";
                        //include "Calendar.php";

                    ?>
                </div>
            </div>
        </div>
    </body>
</html>
