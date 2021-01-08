<?
session_start();
// Include DB Connection
include("../includes/dbConnect.php");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $un = $_REQUEST["txtUserName"];
    $pw = $_REQUEST["txtPassword"];

    $checkUser = "SELECT userName FROM Advisor WHERE userName = '" . $un . "'";
    $checkUserResults = mysqli_query($link, $checkUser);

    if (mysqli_num_rows($checkUserResults) > 0) {
        $checkPassword = "SELECT * FROM Advisor WHERE userName = '" . $un . "' AND password = '" . md5($pw) . "'";
        $checkPasswordResults = mysqli_query($link, $checkPassword);

        if (mysqli_num_rows($checkPasswordResults) > 0) {
            $success .= "<p>Good Password";
            //Create Session Variable
            while ($row = mysqli_fetch_array($checkPasswordResults)) {
                $_SESSION["loggedIn"] = "yes";
                $_SESSION["firstName"] = $row["firstName"];
                $_SESSION["lastName"] = $row["lastName"];
                $_SESSION["userName"] = $row["userName"];
                $_SESSION["idAdvisor"] = $row["idAdvisor"];
            }
            header("Location: private/index.php");
        } else {
            $error .= "<li>Wrong Password</li>";
        }
    } else {
        $error .= "<li>Wrong User</li>";
    }
}
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link href="css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>Login</h1>
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
        <legend>Login</legend>
        <p>
            <label>Username:</label>
            <input type="text" name="txtUserName" id="txtUserName">
        </p>
        <p>
            <label>Password:</label>
            <input type="password" name="txtPassword" id="txtPassword">
        </p>
        <p>
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Log In">
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