var xhr;
if (window.XMLHttpRequest) {
    xhr = new XMLHttpRequest();
} else {
    xhr = new ActiveXObject("Microsoft.XMLHTTP");
}

function registerUser() {
    var firstName = document.getElementById("firstName").value;
    var surname = document.getElementById("surname").value;
    var email = document.getElementById("email").value;
    var password = document.getElementById("password").value;
    var confirmPassword = document.getElementById("confirmPassword").value;

    if (firstName === "" || surname === "" || email === "" || password === "" || confirmPassword === "") {
        document.getElementById("message").innerHTML = "All fields are required.";
        return false;
    }

    if (password !== confirmPassword) {
        document.getElementById("message").innerHTML = "Passwords do not match.";
        return false;
    }

    xhr.open("POST", "register.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var response = xhr.responseText.trim();

            if (response.includes("Registration successful")) {
                // Redirect to bidding.php after successful registration
                window.location.href = "bidding.php";
            } else {
                document.getElementById("message").innerHTML = response;
            }
        }
    };
    xhr.send("firstName=" + encodeURIComponent(firstName) + "&surname=" + encodeURIComponent(surname) + "&email=" + encodeURIComponent(email) + "&password=" + encodeURIComponent(password));
    return false;
}
