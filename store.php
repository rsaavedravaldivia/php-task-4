<?php

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $title = htmlspecialchars($_POST['title']);


    // validate open input text, needs refining

    if (validate_name(htmlspecialchars($_POST['firstname']))) {
        $firstname = htmlspecialchars($_POST['firstname']);
    } else {
        header("Location: index.html?error=invalid_input");
        exit();
    }
    if (validate_name(htmlspecialchars($_POST['lastname']))) {
        $lastname = htmlspecialchars($_POST['lastname']);
    } else {
        header("Location: index.html?error=invalid_input");
        exit();
    }

    $age = htmlspecialchars($_POST['age']);
    $city = htmlspecialchars($_POST['city']);
    $country = htmlspecialchars($_POST['country']);

    if (validate_phone(htmlspecialchars($_POST['phone']))) {

        $temp = htmlspecialchars($_POST['phone']);

        // store value null if string is empty
        if ($temp === "") {
            $phone = null;
        } else { // otherwise store the validated phone number
            $phone = $temp;
        }
    } else {
        header("Location: index.html?error=invalid_input");
        exit();
    }

    $travel_preferences = htmlspecialchars($_POST['travel_preferences']);
    $group_size = htmlspecialchars($_POST['group_size']);
    $budget = htmlspecialchars($_POST['budget']);


    $conn = connect_to_database();
    $stmt = $conn->prepare("INSERT INTO user_data (title, first_name, last_name, age_range, country, city, travel_preferences, group_size, travel_budget, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssssi", $title, $firstname, $lastname, $age, $country, $city, $travel_preferences, $group_size, $budget, $phone);
    $stmt->execute();
    $stmt->close();
    $conn->close();


    header("Location: success.html");
}


function connect_to_database()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "user_information";

    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function validate_name($name)
{
    // Remove leading and trailing whitespace
    $name = trim($name);
    // Check if the name is empty
    if (empty($name)) {
        return false; // Return false if empty
    }
    // Check if the name contains only letters (no spaces, numbers, or symbols)
    if (!preg_match("/^[a-zA-Z]+$/", $name)) {
        return false; // Return false if it contains invalid characters
    }
    if (strlen($name) > 50) {
        return false; // Return false if it exceeds the maximum length
    }
    // Checking for HTML tags
    if (strip_tags($name) !== $name) {
        return false; // Return false if HTML tags are found
    }
    // If all checks pass, return true
    return true;
}

function validate_phone($phone)
{
    if ($phone !== "") {
        // Remove any non-numeric characters
        $cleaned_phone = preg_replace('/[^0-9]/', '', $phone);

        // Check if the cleaned phone number has 10 digits
        if (preg_match('/^[0-9]{10}$/', $cleaned_phone)) {
            return true;
        }

        // add more validations after checking lenght if needed

        return false;
    } else {
        // if user entered nothing, return true to store null after
        return true;
    }

    // Add drop down option to area code
    // add validation to the area code
}
