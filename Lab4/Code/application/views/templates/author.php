<div id="authorPage">
    <div id="authorProfile">
        <img id="authorPhoto" src="<?php echo $author_info['authorImg']; ?>">
        <div id="authorDescription">
        <?php echo $author_info["authorDescription"]; ?>
        </div>
    </div>
    <?php
        if(!$author_info["papers"]){
            echo "<div id='noResult'>No Paper Founded!</div>";
            exit();
        }
    ?>
    <div id="overallResult">
        <p><?php echo "$paperNum papers are found." ?></p>
    </div>
    <table id='papersTable'>
        <tr>
            <th class="PaperTitle">Paper Title</th>
            <th class="PublishYear">Publish Year</th>
            <th class="Conference">Conference</th>
            <th class="Citations">Citations</th>
            <th class="Authors">Author(s)</th>
        </tr>
    <?php
        foreach ($author_info["papers"] as $paper) {
            echo '
        <tr class="dataRow">
            <td class="PaperTitle">'.ucwords($paper["paperTitle"]).'</td>
            <td class="PublishYear">'.$paper["paperPublishYear"].'</td>
            <td class="Conference">'.$paper["conferenceName"].'</td>
            <td class="Citations">'.$paper["citation"].'</td>
            <td class="Authors">
                <ol>';
            foreach($paper["authors"] as $subAuthor){
                echo '
                    <li>
                        <a href="/author/'.$subAuthor["subAuthorID"].'">'.ucwords($subAuthor["subAuthorName"]).'</a>
                    </li>';
            }
            echo '
                </ol>
            </td>';
        }
    ?>
    </table>
        <div id="pagination">
            <button type="button" id="papersPrev" disabled="disabled">Previous</button>
            <span id="pageInfo">Page <span id="currentPage"><?php echo $currentPage?></span> of <?php echo $maxPage ?></span>
            <button type="button" id="papersNext">Next</button>
        </div>
    </div>

