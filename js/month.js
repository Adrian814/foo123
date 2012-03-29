
// static and global
var request = createRequest();


function onLoad()
{
    var curdate = new Date()
    var year = curdate.getYear()
    var month = curdate.getMonth();
    alert("year/month: " + year + " / " + month);
    sendRequest(month, year);
}
/**
 * extracts the month and the year using DOM
 * and decrements them.
 */
function getPreviousMonth()
{
    var month = document.getElementById("month").firstChild.nodeValue;
    var year = document.getElementById("year").firstChild.nodeValue;
    //alert(year);

    // decrement month and year
    switch(month) {
        case "January":
            month=12;
            year--;
            break;
        case "February":
            month=1;
            break;
        case "March":
            month=2;
            break;
        case "April":
            month=3;
            break;
        case "May":
            month=4;
            break;
        case "June":
            month=5;
            break;
        case "July":
            month=6;
            break;
        case "August":
            month=7;
            break;
        case "September":
            month=8;
            break;
        case "October":
            month=9;
            break;
        case "November":
            month=10;
            break;
        case "December":
            month=11;
            break;
        default:
            alert("Error: Unknown month: " + month);
            break;

    }

    //alert("Month/Year: " + month+"/"+year);
    sendRequest(month, year);


}

/**
 * extracts the month and the year using DOM
 * and increments them.
 */
function getNextMonth()
{
    var month = document.getElementById("month").firstChild.nodeValue;
    var year = document.getElementById("year").firstChild.nodeValue;
    //alert(year);

    // increment month and year
    switch(month) {
        case "January":
            month=2;
            break;
        case "February":
            month=3;
            break;
        case "March":
            month=4;
            break;
        case "April":
            month=5;
            break;
        case "May":
            month=6;
            break;
        case "June":
            month=7;
            break;
        case "July":
            month=8;
            break;
        case "August":
            month=9;
            break;
        case "September":
            month=10;
            break;
        case "October":
            month=11;
            break;
        case "November":
            month=12;
            break;
        case "December":
            month=1;
            year++;
            break;
        default:
            alert("Error: Unknown month: " + month);
            break;

    }

    //alert("Month/Year: " + month+"/"+year);
    sendRequest(month, year);

}

function sendRequest(month, year)
{
    
   // var url = "cal.php?month=" +month +
   //                   "&year=" + year;
   var url = "Calendar.php?month=" + month + "&year=" +year;
    request.open("GET", url, true);
    request.onreadystatechange = updateCalendar;
    //alert(url);
    request.send(null);
}


function updateCalendar()
{
    if (request.readyState == 4) {
        if (request.status == 200) {
            var response = request.responseText;
            //alert(response);
            document.getElementById("calendar").innerHTML = response;
        }else {
            var msg = request.getResponseHeader("Status");
            if((msg == null) || (msg.length <= 0))
                alert("Error: Request Status is " + request.status);
            else
                alert(msg);
        }
    }
}


/**
 * creates and returns a request object.
 */
function createRequest()
{
    try {
        request = new XMLHttpRequest();
    } catch(trymicrosoft) {
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP");
        } catch (othermicrosoft) {
            try {
                request = ActiveXObject("Microsoft.XMLHTTP");
            } catch (failed) {
                request = null;
            }
        }
    }

    if (request == null) {
        alert("Error creating request object!");
    } else {
        return request;
    }
}