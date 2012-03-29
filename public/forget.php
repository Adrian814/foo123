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
            $design->createHeader("Demo Kalender > Forget Password", "css/web_tech.css");

            if(isset($_SESSION["email"]))
            {
                echo $_SESSION["email"];
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
                    <h2>Request new password</h2>
                    <p>Enter your eMail address in order to get your login information. </p>
                    <form name="email" action="forget.php" method="post">
                        <input type="text" name="email" value="" />
                        <input type="submit" value="Get Login Data" name="submit" />
                    </form>
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