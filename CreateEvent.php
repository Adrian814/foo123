<?php session_start(); ?>

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
    
    //check the user rights       
    if (!isset($_SESSION["nick"])) {
        echo "<meta http-equiv='refresh' content='0; url=index.php'>";
    }
    if (isset($_SESSION["nick"])) {
        include 'Date.php'; // this file is needed for the date fields of the form
        //form
        echo"
            <h2>Create New Event</h2>
            Please enter all the necessary information about your event!<br><br>
            <form action='index.php' method='post'>
                <table border='0' cellpadding ='5'>
                    <colgroup>
                        <col width='100'>
                        <col>
                    </colgroup>
                    <tr>
                        <td>Title*</td>
                        <td><input name='Title' type='text' size='30' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td>Type*</td>
                        <td><input name='Type' type='text' size='30' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td>Date*</td>
                        <td>"?><?php DateSelector("Sample")?><?php echo "</td>
                    </tr>
                    <tr>
                        <td>Time*</td>
                        <td><input name='Hours' type='text' size='2' maxlength='2'>:<input name='Minutes' type='text' size='2' maxlength='2'><font color='grey'><i> ex: '12:30'<i><font></td>
                    </tr>
                    <tr>
                        <td>Location*</td>
                        <td><input name='Location' type='text' size='30' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td>Organizer</td>
                        <td><input name='Organizer' type='text' size='30' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td>Issue*</td>
                        <td><input name='Issue' type='text' size='30' maxlength='30'></td>
                    </tr>
                    <tr>
                        <td valign='top'>Description</td>
                        <td><textarea name='Description' cols='50' rows='10'></textarea></td>
                    </tr>
                </table><br>
                <input type='submit' name='createEvent_form' value='Submit'>
                <input type='reset' value='Reset'>
            </form><p>
            * mandatory!</p>";
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