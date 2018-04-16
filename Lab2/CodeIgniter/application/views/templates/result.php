<?php
    function echoAuthor($authorID,$authorName,$paperNum,$affiliationID,$affiliationName)
    {
        $affiliationName=$affiliationName ?ucwords($affiliationName): "None";
        $affiliationID=$affiliationID??"00000000";
        $authorName=ucwords($authorName);
        echo "    <tr>
        <td ><a href='/author/$authorID'>$authorName</a></td>
        <td id='widenColumn'>$paperNum</td>
        <td ><ul><li><a href='/affiliation/$affiliationID'>$affiliationName</a></li></ul></td>
    </tr>\n";
    }
?>

<table id='searchResult' align='center'>
    <tr>
        <th>Author Name</th>
        <th id="widenColumn">Total Citations</th>
        <th>Affiliation Name</th>
    </tr>
    <?php
    foreach ($searchResult as $singleAuthor) {
        echoAuthor($singleAuthor["authorID"],$singleAuthor["authorName"],$singleAuthor["paperNum"],$singleAuthor["affiliationID"],$singleAuthor["affiliationName"]);
    }
    ?>
</table>
