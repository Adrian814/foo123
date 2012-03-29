<?php

/**
 * Description of Calendar
 *
 * @author kulturfenster
 *
 * created on 15.10.2009
 */
class Calendar {

    function Calendar()
    {

    }


	    function cal_days_in_month($calendar, $month, $year) 
	    { 
	        return date('t', mktime(0, 0, 0, $month, 1, $year)); 
	    } 



    /**
     *
     * @param int $year
     * @return boolean returns true or false depending on whether it's a leap year.
     */
    function leapYear($year){
        if ($year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0)) return TRUE;
        return FALSE;
    }

    /**
     * Draws a calender for a specific month and year.
     *
     * @param int $month The month which is supposed to be displayed.
     * @param int $year The year which is supposed to be displayed.
     */
    function drawCalendar($month, $year)
    {

        date_default_timezone_set('Europe/Paris');

        $todayMonth = date("m");
        $todayYear = date("Y");
        $todayDay = date("d");

//    echo $month . " " . $year . "<p>";

        //Here we generate the first day of the month
        $first_day = mktime(0,0,0,$month, 1, $year) ;

        //This gets us the month name
        $title = date('F', $first_day) ;

        //Here we find out what day of the week the first day of the month falls on
        $day_of_week = date('D', $first_day) ;

        //Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
        switch($day_of_week){
            case "Sun": $blank = 0; break;
            case "Mon": $blank = 1; break;
            case "Tue": $blank = 2; break;
            case "Wed": $blank = 3; break;
            case "Thu": $blank = 4; break;
            case "Fri": $blank = 5; break;
            case "Sat": $blank = 6; break;
        }

		define('CAL_GREGORIAN', 1); 
        //We then determine how many days are in the current month
        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $month, $year) ;

        //Here we start building the table heads
        echo "<table border=1 width=294 style=empty-cells:show; border-collapse:collapse;>";
        echo "<tr><th colspan=7>";
        echo "<a class='none' href='javascript:getPreviousMonth()'> < </a>";
        echo "<span id='month'>$title</span> <span id='year'>$year </span>";
        echo "<a class='none' href='javascript:getNextMonth()'> > </a></th></tr>";
        echo "<tr><td width=42>Sun</td><td width=42>Mon</td><td width=42>Tue</td><td width=42>Wed</td><td width=42>Thu</td><td width=42>Fri</td><td width=42>Sat</td></tr>";

        //This counts the days in the week, up to 7
        $day_count = 1;

        echo "<tr>";
        //first we take care of those blank days
        while ( $blank > 0 )
        {
            echo "<td></td>"; // &nbsp;
            $blank = $blank-1;
            $day_count++;
        }

        //sets the first day of the month to 1
        $day_num = 1;

        include "user.class.php";
        $user = new userclass();
            


        //count up the days, until we've done all of them in the month
        while ( $day_num <= $days_in_month )
        {
            $user = new userclass();
            // change background when events take place.
            if($user->checkEvent($day_num, $month, $year))
            {
               $date = date("Y-m-d", mktime(0,0,0,$month, $day_num, $year));
                echo "<td id='calendar_event'>";
                echo "<a class='cal' href='DateEvents.php?Date=" .$date ."&Order=Points'>$day_num </a></td>";
            }
            else
                echo "<td> $day_num </td>";


            $day_num++;
            $day_count++;

            //Make sure we start a new row every week
            if ($day_count > 7)
            {
                echo "</tr><tr>";
                $day_count = 1;
            }
        }

        //Finaly we finish out the table with some blank details if needed
        while ( $day_count >1 && $day_count <=7 )
        {
            echo "<td> </td>";
            $day_count++;
        }

        echo "</tr>";
        echo "</table>";
    }
}

// check if params are set
        if(isset($_GET["month"]) && isset($_GET["year"]))
        {
            $month = $_GET["month"];
            $year = $_GET["year"];
        }
        else
        {
            $month = date("m");
            $year = date("Y");

        }

        $cal = new Calendar();
        $cal->drawCalendar($month, $year);

?>
