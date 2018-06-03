<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/static/js/jquery-ui.css">
    <link rel="stylesheet" href="/static/css/main.css">
<!--
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://d3js.org/d3.v4.min.js"></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.0.1/spin.min.js'></script>
-->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.bootcss.com/d3/4.13.0/d3.min.js"></script>
    <script src="/static/js/main.js"></script>
    <?php 
    if(isset($extra) and $extra){
        echo $extra;
    }
    if(isset($script) and $script){
    	echo "\n<script>\n";
    	echo $script;
    	echo "\n   </script>\n";
    }
    ?>
</head>
<body>
    <div id="navBar">
        <ul>
            <li><a class="active" href="/">Home</a></li>
            <li><a href="/author">Author</a></li>
            <li><a href="/paper">Paper</a></li>
            <li><a href="/conference">Conference</a></li>
            <li><a href="/affiliation">Affiliation</a></li>
            <li style="float:right;"><a href="/about">About</a></li>
        </ul>
    </div>
    <h1><?php echo $title; ?></h1>
