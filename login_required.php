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
                <div id="content">
                    <h3>Login Required</h3><p>
                    In order to continue you must <a href="login.php">login</a>
                    or sign up for a new account <a href="signup.php">here</a>.</p>
                    <a href="javascript: history.go(-1)">back</a>
                    </div>
                <div id="calendar">
                    <?php

                        include "cal.php";

                    ?>
                </div>
            </div>
        </div>
    </body>
</html>