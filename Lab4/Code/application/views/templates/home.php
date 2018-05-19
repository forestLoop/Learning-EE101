<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="/static/css/main.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(function(){
            $( "#authorname" ).autocomplete({
                source: "hint.php",
                minLength: 1,
            });
        });
    </script>
</head>
<body>
    <div id="superCenter">
    <h1>Home</h1>

    <div id="homepage" align="center">
        <?php echo form_open('/'); ?>
            <input type="text" id="authorname" name="authorname">
            <input type="submit" id="homeSubmit" value="Search">
        </form>
    </div>
    </div>

</body>
</html>
