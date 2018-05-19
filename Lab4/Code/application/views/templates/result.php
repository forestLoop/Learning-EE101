<?php
    function echoAuthor($authorID,$authorName,$paperNum,$affiliationID,$affiliationName)
    {
        $affiliationName=$affiliationName ?ucwords($affiliationName): "None";
        $affiliationID=$affiliationID??"00000000";
        $authorName=ucwords($authorName);
        echo "    <tr>
        <td class='AuthorName'><a href='/author/$authorID'>$authorName</a></td>
        <td class='PaperNum'>$paperNum</td>
        <td class='AffiliationName'><ul><li><a href='/affiliation/$affiliationID'>$affiliationName</a></li></ul></td>
    </tr>\n";
    }
?>
<div id="searchResult">
    <div id="overallResult"><p><?php echo "$resultNum items have been found." ?></p></div>
    <table id='resultTable'>
        <tr>
            <th class="AuthorName">Author Name</th>
            <th class="PaperNum">Papers</th>
            <th class="AffiliationName">Affiliation Name</th>
        </tr>
        <?php
        foreach ($searchResult as $singleAuthor) {
            echoAuthor($singleAuthor["authorID"],$singleAuthor["authorName"],$singleAuthor["paperNum"],$singleAuthor["affiliationID"],$singleAuthor["affiliationName"]);
          }
        ?>
    </table>
    <button type="button" id="resultPrev">Previous</button>
    <button type="button" id="resultNext">Next</button>
</div>

