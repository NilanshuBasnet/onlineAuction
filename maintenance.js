/*
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function: Contains functions for maintenance tasks such as processing auction items and generating reports.
*/

function processItems() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "processItems.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("result").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function generateReport() {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "generateReport.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("result").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}
