<?
session_start();
if ($_SESSION["loggedIn"] != "yes")
    header("Location: ../login.php");

$fn = $_SESSION["firstName"];
$ln = $_SESSION["lastName"];
$un = $_SESSION["userName"];
$id = $_SESSION["idAdvisor"];
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Private Landing Page</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>The Promised Land</h1>
<nav>
    <? include("../../includes/nav.php") ?>
</nav>

<body>
<p class="home"> Welcome Professor <? echo $fn; ?> <? echo $ln; ?>. Your email address is <? echo $un . "@pct.edu" ?>.
    You have been granted access to the promised land. Have fun!</p>


</body>
</html>