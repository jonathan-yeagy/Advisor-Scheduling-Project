<?
// Include DB Connection
include("../../includes/dbConnect.php");

session_start();
if ($_SESSION["loggedIn"] != "yes")
    header("Location: ../login.php");

$id = $_SESSION["idAdvisor"];

$error = "";
$success = "";
$npw = "";

$queryUserData = "SELECT * FROM Advisor WHERE idAdvisor = " . $id . "";
$queryResult = mysqli_query($link, $queryUserData);
$row = mysqli_fetch_array($queryResult);
$old_un = $row["userName"];
$old_fn = $row["firstName"];
$old_ln = $row["lastName"];
$old_cId = $row["collegeId"];
$idOffice = $row["idOffice"];
$oldpw = $row["password"];

$queryUserData1 = "SELECT * FROM Office WHERE idOffice = " . $idOffice . "";
$queryResult1 = mysqli_query($link, $queryUserData1);
$row = mysqli_fetch_array($queryResult1);
$old_room = $row["roomNumber"];
$old_suite = $row["suiteLetter"];
$old_bc = $row["buildingCode"];
?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Instructor Registration</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>Edit Profile</h1>
<nav>
    <? include("../../includes/nav.php") ?>
</nav>
<body>
<form action="#" method="post">
    <fieldset>
        <legend>Edit Profile</legend>
        <div class="register">
            <p>
                <label>Username:</label>
                <input type="text" name="txtUserName" id="txtUserName" value="<? echo "$old_un" ?>" required>
            </p>
            <p>
                <label>Current Password (Required):</label>
                <input type="password" name="txtCurrentPassword" id="txtPassword" value="<? echo ""; ?>" required>
            </p>
            <p>
                <label>New Password (leave blank to keep current Password):</label>
                <input type="password" name="txtNewPassword" id="txtPassword" value="<? echo ""; ?>">
            </p>
            <p>
                <label>Confirm New Password (leave blank to keep current Password):</label>
                <input type="password" name="txtConfirmPassword" id="txtConfirmPassword" value="<? echo ""; ?>">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    if ($_POST["txtNewPassword"] === $_POST["txtConfirmPassword"]) {
                        $newPasswordSet = 1;
                    } else {
                        $newPasswordSet = 0;
                        echo "<div id=\"error\">Passwords are not the same.</div>";
                    }
                }
                ?>
            </p>
            <p>
                <label>First Name</label>
                <input type="text" name="txtfirstName" id="txtfirstName" value="<? echo "$old_fn"; ?>" required>
            </p>
            <p>
                <label>Last Name</label>
                <input type="text" name="txtlastName" id="txtlastName" value="<? echo "$old_ln"; ?>" required>
            </p>
            <p>
                <label>College ID</label>
                <input type="number" name="numcollegeId" id="numcollegeId" value="<? echo "$old_cId"; ?>" required>
            </p>
            <p>
                <label>Room Number:</label>
                <input type="number" name="txtRoom" id="txtRoom" value="<? echo "$old_room"; ?>" required>
            </p>
            <p>
                <label>Suite:</label>
                <input type="text" name="txtSuite" id="txtSuite" value="<? echo "$old_suite"; ?>">
            </p>
            <p>
                <label>Building:</label>
                <select name="ddlBuilding" id="ddlBuilding" value="<? echo "$old_bc"; ?>" required>
                    <?
                    //loop through DB for all pet types
                    $queryBuilding = "SELECT * FROM Building ORDER BY buildingCode ASC";
                    $queryResult = mysqli_query($link, $queryBuilding);


                    while ($row = mysqli_fetch_array($queryResult)) {
                        echo "<option value=\"" . $row["buildingCode"] . "\"";

                        if ($row["buildingCode"] == $old_bc) {
                            echo "selected=\"selected\">";
                        } else {
                            echo ">";
                        }
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
            <input type="submit" name="btnSubmit" id="btnSubmit" value="Update">
        </p>
    </fieldset>

    <?
    if (strlen($error) > 0) {
        echo "<div id=\"information\">";
        echo "<ul>";
        echo "$error";
        echo "</ul>";
        echo "</div>";
    }
    ?>
</form>
<?
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $curpw = $npw = $_REQUEST["txtCurrentPassword"];
    $un = $_REQUEST["txtUserName"];

    $npw = $_REQUEST["txtNewPassword"];
    $cpw = $_REQUEST["txtConfirmPassword"];
    $fn = $_REQUEST["txtfirstName"];
    $ln = $_REQUEST["txtlastName"];
    $cId = $_REQUEST["numcollegeId"];
    $room = $_REQUEST["txtRoom"];
    $suite = $_REQUEST["txtSuite"];
    $building = $_REQUEST["ddlBuilding"];
    $password = md5($npw);


    $_SESSION["firstName"] = $fn;
    $_SESSION["lastName"] = $ln;
    $_SESSION["userName"] = $un;

    if (md5($curpw) == $oldpw) {


        if ($newPasswordSet == 1) {
            $editOffice = "UPDATE Office SET roomNumber = " . $room . ", suiteLetter = '" . $suite . "', buildingCode = '" . $building . "' WHERE idOffice = " . $idOffice . "";
            mysqli_query($link, $editOffice);

            if (strlen($npw) > 0) {
                $editUser = "UPDATE Advisor SET collegeId=" . $cId . ", firstName='" . $fn . "', lastName='" . $ln . "', password='" . $password . "', userName='" . $un . "' WHERE idAdvisor = $id";
            } else {
                $editUser = "UPDATE Advisor SET collegeId=" . $cId . ", firstName='" . $fn . "', lastName='" . $ln . "', userName='" . $un . "' WHERE idAdvisor = $id";
            }
            mysqli_query($link, $editUser);
            header("Location: index.php");
        } else {
            $error .= "Error, New Passwords Don't Match";
        }

    } else {
        $error .= "Error, Current Password Incorrect";
    }
}

if (strlen($error) > 0) {
    echo "<div id=\"information\">";
    echo "<ul>";
    echo "$error";
    echo "</ul>";
    echo "</div>";
}
?>
</body>
</html>