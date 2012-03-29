
// static and global
var request = createRequest();

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

 //   alert("Month/Year: " + month+"/"+year);
    sendRequest(month, year);


}

function sendRequest(month, year)
{
    
    var url = "cal.php?month=" +month +
                      "&year=" + year;
    request.open("GET", url, true);
    request.onreadystatechange = updateCalendar;
    //alert(url);
    request.send(null);
}


function updateCalendar()
{
    if (request.readyState == 4) {
        if (request.status == 200) {
            //alert("Request.Status: " + request.status);
            var response = request.responseXML;
            var calDiv = document.getElementById("cal");
            //alert(calDiv);
            var element = calDiv.createElement("table");

            createTable(response, element);
            alert("Element: " + element);
            calDiv.appendChild(element);

//            textNode = response.getElementByTagName("table")[0];
//            alert(textNode);
//            element.appendChild(textNode);
//            calDiv.appendChild(element);
        }else {
            alert("Error: Request Status is " + request.status);
        }
    }
    else {
        //alert("ReadyState: " + request.readyState);
    }
}

/**
 * creates a new table
 *
 * @response XML contains the server XML response.
 * @tableElement DOM-Node Contains a new table node.
 */
function createTable(response, tableElement)
{
    var table = tableElement;
    var row = null;
    var cell = null;
    var content = null;

  for(var x = 0; x < 7; x++)
  {
    row = table.createElement["TR"];

    alert("Row: "+ row);

    for(var y=0; y = 7; y++)
    {
      cell = row.createElement("TD");
      content = response.getElementByTagName["TD"][y].childNode.nodeValue;
      alert("Content: " + content);
      cell.appendTextNode(content);
      row.appendChild(cell);
    }
    table.appendChild(row);
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