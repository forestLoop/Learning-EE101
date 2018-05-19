<img id="photo" src="<?php echo $author_info['authorImg']; ?>">
<div id="profile">
    <?php echo $author_info["authorDescription"]; ?>
</div>
<?php
    if(!$author_info["papers"]){
        echo "<div id='noResult'>No Paper Founded!</div>";
        exit();
    }
?>
<table id='papers' align='center'>
    <tr>
        <th id="narrowedColumn">Paper Title</th>
        <th>Publish Year</th>
        <th>Conference</th>
        <th>Citations</th>
        <th style="text-align:center">Author(s)</th>
    </tr>
<?php
    foreach ($author_info["papers"] as $paper) {
        echo '
    <tr>
        <td id="narrowedColumn">'.ucwords($paper["paperTitle"]).'</td>
        <td>'.$paper["paperPublishYear"].'</td>
        <td>'.$paper["conferenceName"].'</td>
        <td>'.$paper["citation"].'</td>
        <td>
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
