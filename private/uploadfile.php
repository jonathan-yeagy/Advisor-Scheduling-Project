<?
include("../../includes/dbConnect.php");

session_start();
if ($_SESSION["loggedIn"] != "yes")
    header("Location: ../login.php");

?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Upload File</title>
    <link href="../css/style.css" rel="stylesheet" type="text/css">
</head>
<h1>Upload csv file</h1>
<nav>
    <? include("../../includes/nav.php") ?>
</nav>
<body>
<?

// Set Session Variables
$fn = $_SESSION["firstName"];
$ln = $_SESSION["lastName"];
$un = $_SESSION["userName"];
$id = $_SESSION["idAdvisor"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fileTempName = $_FILES["myFile"]["tmp_name"];
    $fileName = $_FILES["myFile"]["name"];
    $fileSize = $_FILES["myFile"]["size"];
    $fileType = $_FILES["myFile"]["type"];

    if ($fileSize > 0) {

        $moveTo = "uploads/" . $fileName;
        $error = "";

        if (file_exists($moveTo)) {
            $error .= "File Exists";
        }
        if (strlen($error) == 0) {
            move_uploaded_file($fileTempName, $moveTo);
            $file = fopen($moveTo, "r");
            $rowcount = 1;
            //loop through rows in file
            while (!feof($file)) {

                $row = fgetcsv($file);

                $lastName = $row[0];
                $firstName = $row[1];
                $userName = $row[5];
                $collegeId = $row[3];
                $majorCode = $row[4];

                if ($rowcount > 1) {

                    $characters = "abcdefghijklmnopqrstuvwxyz0123456789";
                    $registrationCode = "";

                    for ($i = 0; $i < 10; $i++) {
                        $registrationCode .= $characters[mt_rand(0, 35)];
                    }

                    $insertStudent = "INSERT INTO Student (collegeId, firstName, lastName, userName, idAdvisor, majorCode, registrationCode) VALUES (" . $collegeId . ", '" . $firstName . "', '" . $lastName . "', '" . $userName . "', " . $id . ", '" . $majorCode . "', '" . $registrationCode . "')";

                    mysqli_query($link, $insertStudent);


                } else {
                    $rowcount++;
                }
            }
            if (feof($file)) {
                unlink($moveTo);
            }
        }

    }

}


?>

<form action="#" method="post" enctype="multipart/form-data">
    <fieldset>
        <legend>Upload File</legend>
        <p>
            <label>Choose File:</label>
            <input type="file" name="myFile" id="myFile" accept=".csv">
            <? echo " $error"; ?>
        </p>
        <p><a href="../templates/AdviseeList.csv">Example</a></p>
        <p>
            <input type="submit" name="btnSubmit" id="btnSubmit" value="upload">
        </p>
    </fieldset>
</form>

<?


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
</body>
</html>