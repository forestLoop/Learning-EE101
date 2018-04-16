<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/main.css">
    <title>Affiliation</title>
</head>
<body>
    <?php
        if(isset($_GET["affiliationid"])){
            $affiliationid=filter_input(INPUT_GET, "affiliationid",FILTER_SANITIZE_MAGIC_QUOTES);
            echo "<h1>Affiliation Page</h1>";
            echo "<div id='noResult'>To be developed.</div>";
        }
     ?>
</body>
</html>
