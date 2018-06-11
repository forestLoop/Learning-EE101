<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="/static/css/main.css">
    <!--
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    -->
    <link rel="shortcut icon" href="/static/favicon.ico" />
    <link rel="bookmark"href="/static/favicon.ico" />
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <link href="/static/css/jquery-ui.css" rel="stylesheet">
    <script src="/static/js/main.js"></script>
</head>
<body>
    <?php echo validation_errors('<div class="error" align="center">','</div>'); ?>
    <div id="magicWrapper">
    <div id="superCenter" align="center">
        <h1>Home</h1>
        <div id="homeSearch" align="center">
            <?php echo form_open('/'); ?>
                <input type="text" id="homeInput" name="queryString" placeholder="What do you want to search?">
                <input type="submit" id="homeSubmit" value="Search">
                <br>
                <div id="searchType">
                    <label for="author">
                        <input id="author" class="input-radio" type="checkbox" name="type[]" value="author" checked>Author
                    </label>
                    <label for="paper">
                        <input id="paper" class="input-radio" type="checkbox" name="type[]" value="paper" checked>Paper
                    </label>
                    <label for="conference">
                        <input id="conference" class="input-radio" type="checkbox" name="type[]" value="conference">Conference
                    </label>
                    <label for="affiliation">
                        <input id="affiliation" class="input-radio" type="checkbox" name="type[]" value="affiliation">Affiliation
                    </label>
                </div>
            </form>
        </div>
    </div>
    </div>
</body>
</html>

