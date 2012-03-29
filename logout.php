<?php session_start();
     //include "ini_set.inc.php";
            include "design.class.php";

            include "user.class.php";
            $user = new userclass();
            $user->checkCookie();
            $nick = $user->logout();
?>
<html>
    <head>
        <?php
           

            $design = new designclass();
            $design->createHeader("Demo Kalender > Logout", "css/web_tech.css");

        ?>


    </head>
    <body>
        <div id="all">

            <?php

                $design->createNavigation("navigation", "navigation_logo", "navigation_title", "navigation_nav", "nav", "navigation_login");

            ?>
            <div id="body">
                <div id="content">
                    <h3>Goodbye</h3>
                    Logout succesfull! Thanks for visiting,
            <?php
                echo $nick;

                echo "<meta http-equiv='refresh' content='3; url=index.php'>";
            ?>
                    <p>Redirection in a few seconds...</p>
                </div>
                <div id="calendar">
                    <?php include "cal.php"; ?>
                </div>
            </div>
        </div>
    </body>
</html>