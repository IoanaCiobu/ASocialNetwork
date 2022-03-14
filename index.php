<?php
$con = mysqli_connect("localhost","root","","social");
if(mysqli_connect_errno()) {
    echo "Failed to connect: ". mysqli_connect_errno();
}
?>
<html>
    <head>
       <title>Social Network</title>
    </head>
    <body>
        Hello!
    </body>
</html>