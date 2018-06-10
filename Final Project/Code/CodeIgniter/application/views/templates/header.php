<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $title; ?></title>
    <link rel="stylesheet" href="/static/css/jquery-ui.css">
    <link rel="stylesheet" href="/static/css/main.css">
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
            <li><a href="/author/7E61D5F6">Author</a></li>
            <li><a href="/paper/80DD088B">Paper</a></li>
            <li><a href="/conference/465F7C62">Conference</a></li>
            <li><a href="/affiliation/0AE9651A">Affiliation</a></li>
            <li style="float:right;"><a href="/about">About</a></li>
        </ul>
    </div>
    <h1><?php echo $title; ?></h1>
