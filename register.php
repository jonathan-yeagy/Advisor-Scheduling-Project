<?
session_start();
// Include DB Connection
include("../includes/dbConnect.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $un = $_REQUEST["txtUserName"];
    $pw = $_REQUEST["txtPassword"];
    $cpw = $_REQUEST["txtConfirmPassword"];
    $fn = $_REQUEST["txtfirstName"];
    $ln = $_REQUEST["txtlastName"];
    $cId = $_REQUEST["numcollegeId"];
    $room = $_REQUEST["txtRoom"];
    $suite = $_REQUEST["txtSuite"];
    $building = $_REQUEST["ddlBuilding"];
    if ($pw == $cpw) {
        $insertOffice = "INSERT INTO Office (roomNumber, suiteLetter, buildingCode) VALUES (" . $room . ", '" . $suite . "', '" . $building . "')";
        mysqli_query($link, $insertOffice);

        $officeID = mysqli_insert_id($link);

        $insertUser = "INSERT INTO Advisor (collegeId, firstName, lastName, userName, password, idOffice) VALUES ('" . $cId . "', '" . $fn . "', '" . $ln . "',  '" . $un . "', '" . md5($pw) . "', " . $officeID . ")";
        mysqli_query($link, $insertUser);

        $_SESSION["idAdvisor"] = mysqli_insert_id($link);
        $_SESSION["loggedIn"] = "yes";
        $_SESSION["firstName"] = $fn;
        $_SESSION["lastName"] = $ln;
        $_SESSION["userName"] = $un;

        $email = $un . "@pct.edu";
        $subject = "Instructor Registration";
        $body = "Thank you for Registering on our Website";
        $from = "admin@crazyyeagydesigns.com";
        mail($email, $subject, $body, $from);


        header("Location: private/index.php");


    } else {
        $error .= "Passwords do not match.";
    }


}
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Instructor Registration</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>Instructor Registration</h1>
<nav>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="register.php">Instructor Register</a></li>
        <li><a href="studentregister.php">Student Register</a></li>
        <li id="login"><a href="login.php">Login</a></li>
    </ul>
</nav>
<body>
<form action="#" method="post">

    <fieldset>
        <legend>Instructor Registration</legend>
        <div class="register">
            <p>
                <label>Username:</label>
                <input type="text" name="txtUserName" id="txtUserName" required>
            </p>
            <p>
                <label>Password:</label>
                <input type="password" name="txtPassword" id="txtPassword" required>
            </p>
            <p>
                <label>Confirm Password:</label>
                <input type="password" name="txtConfirmPassword" id="txtConfirmPassword" required>
            </p>
            <p>
                <label>First Name</label>
                <input type="text" name="txtfirstName" id="txtfirstName" required>
            </p>
            <p>
                <label>Last Name</label>
                <input type="text" name="txtlastName" id="txtlastName" required>
            </p>
            <p>
                <label>College ID</label>
                <input type="number" name="numcollegeId" id="numcollegeId" required>
            </p>
            <p>
                <label>Room Number:</label>
                <input type="number" name="txtRoom" id="txtRoom" required>
            </p>
            <p>
                <label>Suite:</label>
                <input type="text" name="txtSuite" id="txtSuite" required>
            </p>
            <p>
                <label>Building:</label>
                <select name="ddlBuilding" id="ddlBuilding" required>
                    <?
                    //loop through DB for all pet types
                    $queryBuilding = "SELECT * FROM Building ORDER BY buildingCode ASC";
                    $queryResult = mysqli_query($link, $queryBuilding);

                    while ($row = mysqli_fetch_array($queryResult)) {
                        echo "<option value=\"" . $row["buildingCode"] . "\">";
                        echo $row["buildingCode"];
                        echo " - ";
                        echo $row["buildingName"];
                        echo "</option>";
                    }
                    ?>
                </select>
            </p>
        </div>
        <p>
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Register">
        </p>
    </fieldset>

    <?
    if (strlen($error) > 0 || strlen($success) > 0) {
        echo "<div id=\"information\">";
        echo "<ul>";
        echo "$error";
        echo "$success";
        echo "</ul>";
        echo "</div>";
    }
    ?>
</form>
</body>
</html>