
<html>
<head>
<meta http-equiv="refresh" content="30" >
</head>
<body>

<?php
if (!file_exists("/var/www/status/pid.txt"))
{
    echo ' <form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post"
    enctype="multipart/form-data">
    <label for="file">Filename:</label>
    <input type="file" name="file" id="file"><br>
    <input type="submit" name="submit" value="Submit">
    </form> ';
}
else
{
    echo ' Printer is busy <br>';
}

if (isset($_POST['submit']))
{
    if ($_FILES["file"]["error"] > 0)
    {
        echo "Error: " . $_FILES["file"]["error"] . "<br>";
    }
    else
    {
        $uploads_dir = '/var/www/upload';
        $tmp_name = $_FILES["file"]["tmp_name"];
        $name = $_FILES["file"]["name"];
        $upload_name = "$uploads_dir/$name";
        echo "Upload: " . $_FILES["file"]["name"] . "<br>";
        echo "Type: " . $_FILES["file"]["type"] . "<br>";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
        echo "Stored in: " . $_FILES["file"]["tmp_name"] . "<br>";
        echo "Move to: " . $upload_name . "<br>";
        if (move_uploaded_file($tmp_name, $upload_name)) {
            echo "move successful <br>";
            exec("nohup /usr/lib/cgi-bin/3dprint.sh " . $upload_name . " 1>/var/www/status/clientoutput.txt 2>&1 & echo $!", $op);
            echo "PID " . ((int)$op[0]);
            sleep(1);
        }
        else
        {
            echo "move error <br>";
        }
    }
}
?>

output log:<br>
<pre>
<?php include("/var/www/status/clientoutput.txt"); ?>
</pre>

</body>
</html>
