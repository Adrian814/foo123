<?php
session_start();
?>

<html>
    <head>
        <?php
            include "ini_set.inc.php";
            include "design.class.php";
            include "user.class.php";

            $user = new userclass();
            $user->checkCookie();

            $design = new designclass();
            $design->createHeader("Demo Kalender", "css/web_tech.css");

            if(isset($_POST["createEvent_form"]))
            {
                include "EventCreated.php";
            }
        ?>

    </head>
    <body>
        <div id="all">

            <?php
            
                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <?php
                    echo "<br>";
                $db = mysql_connect("localhost", "siudaa", "saihiechoo3E") or die(mysql_error());
        mysql_select_db("proj2009-siudaa", $db) or die(mysql_error());
                //$db = mysql_connect('localhost', 'phpmyadmin', 'mausfalle');

    $participantsSelected = 0;
    $pointsSelected = 0;
    $Seite = 0;

    if (!$db) {
        echo 'Error: Could not connect to database.';
        exit;
    }

    $mysql = mysql_select_db('proj2009-siudaa');
    if(!$mysql) {
        echo 'Cannot select database.';
        exit;
    }

    // actions, when the up arrow was clicked for an event
    if (isset($_POST['ArrowUp'])) {
        if (!isset($_SESSION['nick']))
            echo "<font color='red'>Please log in to be a supporter of this event!</font><br><br>";
        else {
            $EventId = $_POST['ArrowUp'];
            $Supporter = $_SESSION['nick'];
            $Opponent = $_SESSION['nick'];
            $query = 'SELECT * FROM Support WHERE EventId = "' . $EventId . '" AND Supporter = "' . $Supporter . '"';
            $result = mysql_query($query);
            $anz1 = mysql_num_rows($result);
            $query = 'SELECT * FROM Opposition WHERE EventId = "' . $EventId . '" AND Opponent = "' . $Opponent . '"';
            $result = mysql_query($query);
            $anz2 = mysql_num_rows($result);
            if ($anz1 != 0)
                echo "<font color='red'>You are already supporter of this event!</font><br><br>";
            elseif ($anz2 != 0)
                echo "<font color='red'>You are already opponent of this event, so you can't be supporter!</font><br><br>";
            else {
                $query = "INSERT INTO Support (EventId, Supporter)"
                        ."VALUES (\"".$EventId."\","
                        ."\"".$_SESSION["nick"]."\");";
                mysql_query($query);
                $query = "SELECT Points, Supporter, Opponents FROM `Events` WHERE EventId=$EventId";
                //echo $query ."<p>";
                $result = mysql_query($query);
                if ($result) {
                    $row = mysql_fetch_array($result);
                    $supporterBefore = $row['Supporter'];
                    $supporterNow = $supporterBefore + 1;
                    $query = "UPDATE  `proj2009-siudaa`.`Events` SET `Supporter` = $supporterNow WHERE `Events`.`EventId` =$EventId;";
                    mysql_query($query);
                }
                $query = "SELECT Points, Supporter, Opponents FROM `Events` WHERE EventId=$EventId";
                $result = mysql_query($query);
                if ($result) {
                    $row = mysql_fetch_array($result);
                    $points = $row['Supporter'] - $row['Opponents'];
                    $query = "UPDATE  `proj2009-siudaa`.`Events` SET `Points` = $points WHERE `Events`.`EventId` = $EventId;";
                    mysql_query($query);
                }
            }
        }
    }

    // actions, when the down arrow was clicked for an event
    if (isset($_POST['ArrowDown'])) {
        if (!isset($_SESSION['nick']))
            echo "<font color='red'>Please log in to be an opponent of this event!</font><br><br>";
        else {
            $EventId = $_POST['ArrowDown'];
            $Supporter = $_SESSION['nick'];
            $Opponent = $_SESSION['nick'];
            $query = 'SELECT * FROM Support WHERE EventId = "' . $EventId . '" AND Supporter = "' . $Supporter . '"';
            $result = mysql_query($query);
            $anz1 = mysql_num_rows($result);
            $query = 'SELECT * FROM Opposition WHERE EventId = "' . $EventId . '" AND Opponent = "' . $Opponent . '"';
            $result = mysql_query($query);
            $anz2 = mysql_num_rows($result);
            if ($anz1 != 0)
                echo "<font color='red'>You are already supporter of this event, so you can't be opponent!</font><br><br>";
            elseif ($anz2 != 0)
                echo "<font color='red'>You are already opponent of this event!</font><br><br>";
            else {
                $query = "INSERT INTO Opposition (EventId, Opponent)"
                        ."VALUES (\"".$EventId."\","
                        ."\"".$_SESSION["nick"]."\");";
                mysql_query($query);
                $query = "SELECT Points, Supporter, Opponents FROM `Events` WHERE EventId=$EventId";
                //echo $query ."<p>";
                $result = mysql_query($query);
                if ($result) {
                    $row = mysql_fetch_array($result);
                    $opponentsBefore = $row['Opponents'];
                    $opponentsNow = $opponentsBefore + 1;
                    $query = "UPDATE  `proj2009-siudaa`.`Events` SET `Opponents` = $opponentsNow WHERE `Events`.`EventId` =$EventId;";
                    mysql_query($query);
                }
                $query = "SELECT Points, Supporter, Opponents FROM `Events` WHERE EventId=$EventId";
                $result = mysql_query($query);
                if ($result) {
                    $row = mysql_fetch_array($result);
                    $points = $row['Supporter'] - $row['Opponents'];
                    $query = "UPDATE  `proj2009-siudaa`.`Events` SET `Points` = $points WHERE `Events`.`EventId` = $EventId;";
                    mysql_query($query);
                }
            }
        }
    }

    // actions, when the join button was clicked for an event
    if (isset($_POST['Join'])) {
        if (!isset($_SESSION['nick']))
            echo "<font color='red'>Please log in to join this event!</font><br><br>";
        else {
            $EventId = $_POST['Join'];
            $Participant = $_SESSION['nick'];
            $query = 'SELECT * FROM Participation WHERE EventId = "' . $EventId . '" AND Participant = "' . $Participant . '"';
            $result = mysql_query($query);
            $anz = mysql_num_rows($result);
            if ($anz == 0) {
                $query = "INSERT INTO Participation (EventId, Participant)"
                        ."VALUES (\"".$EventId."\","
                        ."\"".$_SESSION["nick"]."\");";
                mysql_query($query);
                $query = "SELECT Participants FROM `Events` WHERE EventId=$EventId";
                $result = mysql_query($query);
                if ($result) {
                    $row = mysql_fetch_array($result);
                    $participantsBefore = $row['Participants'];
                    $participantsNow = $participantsBefore + 1;
                    $query = "UPDATE  `proj2009-siudaa`.`Events` SET `participants` = $participantsNow WHERE `Events`.`EventId` =$EventId;";
                    mysql_query($query);
                }
            }
            else
                echo "<font color='red'>You are already participant of this event!</font><br><br>";
        }
    }

    $date = $_GET['Date'];

    // prepares the query for the order of the list 
    if (isset($_GET['Order'])) {
        if (($_GET['Order']) == "Points") {
        $query = 'SELECT * FROM `Events` WHERE `Events`.`Date` =' . '"' . $date . '"' . 'ORDER BY `Points` DESC';
        $pointsSelected="selected";
        $Order="Points";
        }
        elseif (($_GET['Order']) == "Participants") {
        $query = 'SELECT * FROM `Events` WHERE `Events`.`Date` =' . '"' . $date . '"' . 'ORDER BY `Participants` DESC';
        $participantsSelected="selected";
        $Order="Participants";
        }
    }
    else {
        $query = 'SELECT * FROM `Events` WHERE `Events`.`Date` =' . '"' . $date . '"' . 'ORDER BY `Points` ASC';
        $pointsSelected="selected";
        $Order="Points";
    }
    
    //page navigation
    if (!isset($_REQUEST['Total'])) {
        $result = mysql_query($query);
        $Total = mysql_num_rows($result);
        unset($result);
    }
    else
        $Total = $_REQUEST['Total'];

    if (!isset($_REQUEST['Page']))
        $Page = 1;
    else
        $Page = $_REQUEST['Page'];
    if ($Page > $Total)
        $Page = $Total;

    $PerPage = 5;
    $LinkNumber = 3;
    $PageNumber = ceil($Total/$PerPage);
    if ($LinkNumber % 2 == 0)
        $LinkNumber++;
    $NumericLinks = ($LinkNumber - 1) / 2;
    $url = $_SERVER['PHP_SELF'] . "?Date=$date"."&Order=$Order";

    $query .= " limit ". ($Page * $PerPage - $PerPage) .", ".$PerPage;
    $result = mysql_query($query);

    $SkipCharactersFront = '';
    $SkipCharactersBack = '';
    $BeginningLink = '';
    $EndLink = '';
    $BackLink = '';
    $ContinueLink = '';
    $ViewableLinks = '';

    if ($PageNumber > 1) {
        $Nr = $Page - $NumericLinks;
        $Display = 0;
        while ($Nr <= $PageNumber) {
            if ($Nr < 1) {
                $Nr++;
                continue;
            }
            elseif ($Nr > $Page + $NumericLinks)
                break;
        if ($Nr == $Page)
            $ViewableLinks .= "<font style='font-weight:bold'> $Nr </font>";
        else
            $ViewableLinks .= '<a  class="adi" href="'.$url.'&Page='.$Nr.'&Total='.$Total.'">'.$Nr.'</a>';
        $Nr++;
        $Display++;
        }
    }

    if ($Page > 1) {
        $BeginningLink = '<a class="adi" href="'.$url.'&Page=1&Total='.$Total.'">Beginning </a>' . ' ';
        if ($Page - 1 > 1)
          $BackLink = ' ' . '<a  class="adi"href="'.$url.'&Page='.($Page - 1).'&Total='.$Total.'">Back </a>';
    }

    if ($Page < $PageNumber) {
        $EndLink = ' ' . '<a class="adi" href="'.$url.'&Page='.$PageNumber.'&Total='.$Total.'"> End</a>';
        if ($Page + 1 < $PageNumber)
        $ContinueLink = '<a class="adi" href="'.$url.'&Page='.($Page+1).'&Total='.$Total.'"> Continue</a>' . ' ';
    }

    if ($Page - $NumericLinks > 1)
        $SkipCharactersFront = '... ';
    if ($Page + $NumericLinks < $PageNumber)
        $SkipCharactersBack = ' ...';

    $Nav = $BeginningLink;
    $Nav .= $BackLink;
    $Nav .= $SkipCharactersFront;
    $Nav .= $ViewableLinks;
    $Nav .= $SkipCharactersBack;
    $Nav .= $ContinueLink;
    $Nav .= $EndLink;
    
    //select menu for order             
    echo "
    <table width='100%'>
    <td>
        <form action='DateEvents.php?Date=$date' method='get'>
            <input type='hidden' name='Date' value='$date'>
            <label for='Order'>Sort by:</label>
                        <select type='submit' name='Order' size='1' OnChange ='submit()'>
                            <option $pointsSelected>Points</option>
                            <option $participantsSelected>Participants</option>
                        </select>
        </form>
    </td>
    <td>
        <p align='right'>$Nav<p>
    </td>
    </table>
    ";

    // generates the list of events for a specific date
    $result = mysql_query($query);
    if ($result) {
    $num_results = mysql_num_rows($result);
    for ($i=0; $i < $num_results; $i++) {
        $row = mysql_fetch_array($result);
        $Description = substr($row['Description'], 0, 200);

    echo "
    <form action='DateEvents.php?Date=$date&Order=$Order&Page=$Page&Total=$Total' method='post'>
        <input type='hidden' name='Date' value='$date'>
        <table>
            <tr>
                <td width='6%' align='center'>
                    <button type='submit' name='ArrowUp' type='button' id='ArrowUp' value='".$row['EventId']."'></button>
                </td>
                <td width='80%' style='font-weight:bold'>
                    <a href=EventProfile.php?EventId=". $row['EventId'] ." class='adi'>"
                    .$row['Title']
                    ." [".$row['Type']."]: </a>"
                    . "".$row['Participants']." participant(s)";
                    if (isset($_SESSION['nick'])) {
                        $anz = mysql_num_rows(mysql_query("SELECT * FROM Participation WHERE EventId = \"".$row['EventId']."\" AND Participant = \"".$_SESSION['nick']."\""));
                        if ($anz == 0)
                            echo " <button name='Join' type='submit' value='".$row['EventId']."'>Join!</button>";
                        else
                            echo " <button name='Join' type='submit' value='".$row['EventId']."' disabled>Join!</button>";
                    }
                    else
                        echo " <button name='Join' type='submit' value='".$row['EventId']."' disabled>Join!</button>";
            echo "
                </td>
                <td width='5%' style='font-weight:bold' align='right'>"
                    .date('d.m.o', strtotime($row['Date']))."
                </td>
            </tr>
            <tr>
                <td align='center' style='font-weight:bold; font-size: 120%; color='red';'>
                    ".$row['Points']."
                </td>
                <td colspan='2', rowspan='2' valign='top' id='event_descr'>
                    $Description
                    <br><a class='adi' href='"."EventProfile.php?EventId=".$row['EventId']."'style='font-style:italic;'> more...<a>
                </td>
            </tr>
            <tr>
                <td align='center'>
                    <button type='submit' name='ArrowDown' type='button' id='ArrowDown' value='".$row['EventId']."' align='top'></button>
                </td>
            </tr>
    ";
    }
    }
    echo "
        </table>
    </form>
    ";
    
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