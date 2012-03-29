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
    
    //check the rights of the user                
    if (!isset($_SESSION["nick"])) {
        echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    }
    if (isset($_SESSION["nick"])) {
        if($_SESSION["nick"] != "Admin") {
            echo "<meta http-equiv='refresh' content='0; url=index.php'>";
        }
        if($_SESSION["nick"] == "Admin") {
            
    $db = mysql_connect("localhost", "siudaa", "saihiechoo3E") or die(mysql_error());
    mysql_select_db("proj2009-siudaa", $db) or die(mysql_error());
    //$db = mysql_connect('localhost', 'phpmyadmin', 'mausfalle');

    if (!$db) {
        echo 'Error: Could not connect to database.';
        exit;
    }

    $mysql = mysql_select_db('proj2009-siudaa');
    if(!$mysql) {
        echo 'Cannot select database.';
        exit;
    }
    
    $dateSelected = 0;
    $lastSelected = 0;
    $nickSelected = 0;
    $Seite = 0;

    // prepares the query for the order of the list 
    if (isset($_GET['Order'])) {
        if (($_GET['Order']) == "Date") {
            $query = 'SELECT * FROM `user` ORDER BY `Date` ASC';
            $dateSelected="selected";
            $Order="Date";
        }
        elseif (($_GET['Order']) == "Last Name") {
        $query = 'SELECT * FROM `user` ORDER BY `last` ASC';
        $lastSelected="selected";
        $Order="Last Name";
        }
        elseif (($_GET['Order']) == "Nickname") {
        $query = 'SELECT * FROM `user` ORDER BY `nick` ASC';
        $nickSelected="selected";
        $Order="Nickname";
        }
    }
    else {
        $query = 'SELECT * FROM `user` ORDER BY `Date` ASC';
        $dateSelected="selected";
        $Order="Date";
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

    $PerPage = 20;
    $LinkNumber = 3;
    $PageNumber = ceil($Total/$PerPage);
    if ($LinkNumber % 2 == 0)
        $LinkNumber++;
    $NumericLinks = ($LinkNumber - 1) / 2;
    $url = $_SERVER['PHP_SELF'] . "?Order=$Order";

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
            <form action='adminuser.php' method='get'>
                <label for='Order'>Sort by:</label>
                    <select type='submit' name='Order' size='1' OnChange ='submit()'>
                        <option $dateSelected>Date</option>
                        <option $lastSelected>Last Name</option>
                        <option $nickSelected>Nickname</option>
                    </select>
            </form>
        </td>
        <td>
            <p align='right'>$Nav<p>
        </td>
        </table>
    ";

    // check, if a checkbox of an user is checked, and if yes, delete his entry
    for ($i=0; $i < $PerPage-1; $i++) {
        $currentcheckbox = "deletecheckbox$i";
        if (!isset($_POST[$currentcheckbox]))
            ;
        elseif (isset($_POST[$currentcheckbox]))
            mysql_query("DELETE FROM `user` WHERE nick = \"$_POST[$currentcheckbox]\"");
    }

    // user table
    echo "
        <form action='adminuser.php?Order=$Order&Page=$Page&Total=$Total' method='post'>
            <table cellpadding='3'>
                <tr align='left' bgcolor='lightgrey'>
                    <th>
                        Select
                    </th>
                    <th>
                        First Name
                    </th>
                    <th>
                        Last Name
                    </th>
                    <th>
                        Nickname
                    </th>
                    <th>
                        E-mail address
                    </th>
                    <th>
                        Registration Date
                    </th>
                </tr>
    ";
        
    $result = mysql_query($query);
    if ($result) {
        $num_results = mysql_num_rows($result);
        for ($i=0; $i < $num_results; $i++) {
            $row = mysql_fetch_array($result);

            echo "
                <tr>
                    <td>
                        <input type='checkbox' name='deletecheckbox$i' value='".$row['nick']."'>
                    </td>
                    <td>
                        ".$row['first']."
                    </td>
                    <td>
                        ".$row['last']."
                    </td>
                    <td>
                        ".$row['nick']."
                    </td>
                    <td>
                        ".$row['email']."
                    </td>
                    <td>
                        ".date('d.m.o', strtotime($row['date']))."
                    </td>
                </tr>
            ";
        }
    }
    
    echo "
        </table>
        <input type='submit' value='Delete'>
        </form>
    ";

    }
    }
    
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