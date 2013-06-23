<html>
<head>
<meta http-equiv="refresh" content="30" >
</head>
<body>

<?php
if (isset($_POST['cancel']))
{
    exec("nohup /usr/lib/cgi-bin/3dprintcancel.sh " . $_POST['cancel'] . " & echo $!", $op);
    #echo "PID " . ((int)$op[0]);
    sleep(1);
}
else if (isset($_POST['submit']))
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
            exec("nohup /usr/lib/cgi-bin/3dprint.sh " . $upload_name . " & echo $!", $op);
            echo "PID " . ((int)$op[0]);
            sleep(1);
        }
        else
        {
            echo "move error <br>";
        }
    }
}
exec("wget http://192.168.1.12:8000/image/jpeg.cgi -O /var/www/upload/cam.jpg > /dev/null 2>&1 &");

$json = exec("/usr/lib/cgi-bin/3dstatus.sh");
#var_dump(json_decode($json, true));
$obj = json_decode($json);
if($obj != NULL)
{
    $count = count($obj, 1) - 1;
    $id = $obj[$count]->{'id'};
    $state = $obj[$count]->{'state'};
    $jobname = $obj[$count]->{'name'};
    $progressname = $obj[$count]->{'progress'}->{'name'};
    $progressnum = $obj[$count]->{'progress'}->{'progress'};

}
else
{
    $id = ''; $state = ''; $progressname = ''; $progressnum = '';
    $jobname = '';
}

if (!file_exists("/var/www/status/pid.txt"))
{
    echo "Printer is not busy. Select file and print.<br>";
    echo ' <form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post"
    enctype="multipart/form-data">
    <label for="file">Filename:</label>
    <input type="file" name="file" id="file"><br>
    <input type="submit" name="submit" value="Print">
    </form> ';
}
else
{
    echo ' Printer is busy <br>';
    echo ' <form action="' . htmlentities($_SERVER['PHP_SELF']) . '" method="post">
        <input type="hidden" name="cancel" value="' . $id . '">
        <input type="submit" value="Cancel Print"> </form> <br>';
}
echo "job id: " . $id . "<br>";
echo "job name: " . $jobname . "<br>";
echo "state: " . $state . "<br>";
echo "phase: " . $progressname . "<br>";
echo "progress: " . $progressnum . "<br>";
?>
<br>
<img src="/upload/cam.jpg" alt="webcam"><br>

<?php 
if (file_exists("/var/www/status/pid.txt")) 
{
    echo "output log:<br>";
    echo "<pre>";
    include("/var/www/status/clientoutput.txt"); 
    echo "</pre>";
}
?>

</body>
</html>
