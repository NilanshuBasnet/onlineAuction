// Create XMLHttpRequest object
var xhr;
if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
} else {
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

function processLogin() {
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;

    xhr.open("POST", "login.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText;
            if (response === "success") {
                window.location.href = "bidding.php"; // Redirect to bidding page on success
            } else {
                document.getElementById("loginMessage").innerHTML = response;
            }
        }
    };
    xhr.send("email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));

    return false; // Prevent form from submitting the traditional way
}
