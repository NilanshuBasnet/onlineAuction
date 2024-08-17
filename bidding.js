/*
 Author: Nilanshu Basnet
 StudentID: 104346575
 Main Function:
*/

// Load items immediately
loadItems();

// Load items every 5 seconds
setInterval(loadItems, 5000);

function loadItems() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "fetchItems.php", true);
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            document.getElementById("itemsList").innerHTML = xhr.responseText;
        }
    };
    xhr.send();
}

function placeBid(itemID) {
    var newBid = prompt("Enter your bid amount:");
    if (newBid) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "placeBid.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText);
            }
        };
        xhr.send("itemID=" + itemID + "&bidPrice=" + newBid);
    }
}

function buyItNow(itemID) {
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "buyItNow.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            alert(xhr.responseText);
        }
    };
    xhr.send("itemID=" + itemID);
}