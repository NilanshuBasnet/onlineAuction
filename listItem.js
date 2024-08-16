function toggleOtherCategoryField() {
    var categorySelect = document.getElementById('category');
    var otherCategoryField = document.getElementById('otherCategoryField');
    
    if (categorySelect.value === 'Other') {
        otherCategoryField.style.display = 'block';
    } else {
        otherCategoryField.style.display = 'none';
    }
}

function listItem() {
    var days = parseInt(document.getElementById('days').value);
    var hours = document.getElementById('hours').value;
    var minutes = document.getElementById('minutes').value;
    var resultDiv = document.getElementById('result');
    
    // Validate days input
    if (isNaN(days) || days < 1) {
        resultDiv.innerHTML = "<p class='errormsg'>Error: Please enter a valid number of days (greater than 0).</p>";
        return; // Stop the function if validation fails
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "listItem.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    var form = document.getElementById('listingForm');
    var formData = new FormData(form);

    // Convert FormData to URLSearchParams
    var params = new URLSearchParams(formData).toString();

    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            resultDiv.innerHTML = xhr.responseText;
        }
    };

    xhr.send(params);
}

// Function to dynamically populate categories (to be called on page load or category selection)
function populateCategories() {
    var xhr = new XMLHttpRequest();
    xhr.open("GET", "getCategories.php", true);  // PHP script to fetch categories
    xhr.onload = function() {
        if (xhr.status === 200) {
            var categories = JSON.parse(xhr.responseText);
            var categorySelect = document.getElementById('category');
            categorySelect.innerHTML = '<option value="Other">Other</option>';  // Include 'Other' option
            categories.forEach(function(category) {
                var option = document.createElement('option');
                option.value = category;
                option.textContent = category;
                categorySelect.appendChild(option);
            });
        }
    };
    xhr.send();
}

document.addEventListener('DOMContentLoaded', function() {
    populateCategories();
    toggleOtherCategoryField();  // Ensure the 'Other' field is hidden initially
});
