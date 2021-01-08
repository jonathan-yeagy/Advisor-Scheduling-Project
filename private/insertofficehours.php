<?
// Include DB Connection
include("../../includes/dbConnect.php");

session_start();
if ($_SESSION["loggedIn"] != "yes")
    header("Location: ../login.php");

$id = $_SESSION["idAdvisor"];


//Time Function
function getTimeList()
{
    $startHour = 8;
    $endHour = 21;

    for ($hour = $startHour; $hour <= $endHour; $hour++) {
        $newHour = $hour > 12 ? $hour - 12 : $hour;
        $midday = $hour > 11 ? "PM" : "AM";
        for ($minute = 0; $minute <= 45; $minute += 15) {
            $newMinute = $minute == 0 ? "00" : $minute;

            echo "<option>" . $newHour . ":" . $newMinute . " " . $midday . "</option>";
        }
    }
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    foreach ($_REQUEST["day"] as $day) {
        $start = $day . "S";
        $end = $day . "E";

        $startTime = $_REQUEST["" . $start . ""];
        $endTime = $_REQUEST["" . $end . ""];

        $compareStart = strtotime($startTime);
        $compareEnd = strtotime($endTime);

        if ($compareStart >= $compareEnd) {
            $error = "Start time is after end time.";
        } else {
            $insertTime = "INSERT INTO OfficeHours (startTime, endTime, weekDay, idAdvisor) VALUES ('" . $startTime . "', '" . $endTime . "', '" . $day . "', " . $id . ")";
            mysqli_query($link, $insertTime);
        }


    }

}
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Office Hours</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>Insert Office hours</h1>
<nav>
    <? include("../../includes/nav.php") ?>
</nav>
<body>
<form action="#" method="post">
    <fieldset>
        <legend>Set office Hours</legend>
        <table>
            <tr>
                <td>
                    <label>Monday</label>
                    <input type="checkbox" name="day[]" id="day[]" value="m">
                </td>
                <td>
                    <label>Tuesday</label>
                    <input type="checkbox" name="day[]" id="day[]" value="t">
                </td>
                <td>
                    <label>Wednesday</label>
                    <input type="checkbox" name="day[]" id="day[]" value="w">
                </td>
                <td>
                    <label>Thursday</label>
                    <input type="checkbox" name="day[]" id="day[]" value="h">
                </td>
                <td>
                    <label>Friday</label>
                    <input type="checkbox" name="day[]" id="day[]" value="f">
                </td>
            </tr>

            <tr>
                <td>
                    <label for="mS">Start Time</label>
                    <select name="mS" id="mS"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="tS">Start Time</label>
                    <select name="tS" id="tS"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="wS">Start Time</label>

                    <select name="wS" id="wS"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="hS">Start Time</label>
                    <select name="hS" id="hS"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="fS">Start Time</label>
                    <select name="fS" id="fS"><? getTimeList(); ?></select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="mE">End Time</label>
                    <select name="mE" id="mE"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="tE">End Time</label>
                    <select name="tE" id="tE"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="wE">End Time</label>
                    <select name="wE" id="wE"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="hE">End Time</label>
                    <select name="hE" id="hE"><? getTimeList(); ?></select>
                </td>
                <td>
                    <label for="fE">End Time</label>
                    <select name="fE" id="fE"><? getTimeList(); ?></select>
                </td>
            </tr>
        </table>
        <p>
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Submit">
        </p>
    </fieldset>
</form>
<?

echo $error;

$queryUserData = "SELECT * FROM OfficeHours WHERE idAdvisor = " . $id . " ORDER BY weekDay ASC";
$queryResult = mysqli_query($link, $queryUserData);

if (mysqli_num_rows($queryResult) > 0) {
    echo "<table>";
    echo "<tr>";
    echo "<th>Start Time</th>";
    echo "<th>End Time</th>";
    echo "<th>Day</th>";
    echo "</tr>";
    while ($row = mysqli_fetch_array($queryResult)) {
        echo "<tr>";
        echo "<td>" . $row["startTime"] . "</td>";
        echo "<td>" . $row["endTime"] . "</td>";
        echo "<td>";
        if ($row["weekDay"] == "m") {
            echo "Monday";
        }
        if ($row["weekDay"] == "t") {
            echo "Tuesday";
        }
        if ($row["weekDay"] == "w") {
            echo "Wednesday";
        }
        if ($row["weekDay"] == "h") {
            echo "Thursday";
        }
        if ($row["weekDay"] == "f") {
            echo "Friday";
        }
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
}

?>

</body>
</html>
	