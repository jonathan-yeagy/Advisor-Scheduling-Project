<?
include("../../includes/dbConnect.php");

session_start();
if ($_SESSION["loggedIn"] != "yes")
    header("Location: ../login.php");

// Set Session Variables
$id = $_SESSION["idAdvisor"];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Email Students</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>Email Students</h1>
<nav>
    <? include("../../includes/nav.php") ?>
</nav>
<body>
<form action="#" method="post">
    <fieldset>
        <legend>Custom Message</legend>
        <p>
            <textarea name="txtMessage" id="txtMessage" rows="10" cols="50"></textarea>
        </p>
        <p>
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Send">
        </p>
    </fieldset>
</form>
</body>
<?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = $_REQUEST["txtMessage"];

    $queryUserData = "SELECT * FROM Student WHERE idAdvisor = " . $id . "";
    $queryResult = mysqli_query($link, $queryUserData);

    while ($row = mysqli_fetch_array($queryResult)) {
        $email = $row["userName"] . "@pct.edu";
        $fn = $row["firstName"];
        $ln = $row["lastName"];
        $code = $row["registrationCode"];


        $subject = "BWM250 Test";
        $body = "Dear " . $fn . " " . $ln . ", \n" . $message . " \nYour registration code is " . $code . ". Do not lose this code. You can register at bwm250.crazyyeagydesigns.com/scheduling/studentregister.php";


        mail($email, $subject, $body);
    }
    echo "Email sent out successfully.";
}


$queryUserData = "SELECT * FROM Student WHERE idAdvisor = " . $id . "";
$queryResult = mysqli_query($link, $queryUserData);

if (mysqli_num_rows($queryResult) > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Last Name</th>";
    echo "<th>First Name</th>";
    echo "<th>Email</th>";
    echo "</tr>";
    while ($row = mysqli_fetch_array($queryResult)) {
        echo "<tr>";
        echo "<td>" . $row["lastName"] . "</td>";
        echo "<td>" . $row["firstName"] . "</td>";
        echo "<td>" . $row["userName"] . "@pct.edu</td>";
        echo "</tr>";
    }
    echo "</table>";
}


?>
</html>