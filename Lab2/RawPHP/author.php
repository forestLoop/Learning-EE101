<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Author</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
        function handleOnePaper($row,$conn,$noCitation=false){
            $paperID=$row["PaperID"];
            $paperTitle=$row["Title"];
            $paperPublishYear=$row["PaperPublishYear"];
            $conferenceID=$row["ConferenceID"];
            $conferenceName=$row["ConferenceName"];
            $citation=$noCitation?0:$row["citation"];
            echo "<tr>
                    <td id=\"narrowedColumn\">".ucwords($paperTitle)."</td>
                    <td>$paperPublishYear</td>
                    <td>$conferenceName</td>
                    <td>$citation</td>
                    <td>
                        <ol>
                    ";
            $queryForAuthors="
                SELECT authors.AuthorName,paper_author_affiliation.*
                FROM authors, (SELECT * FROM paper_author_affiliation WHERE paperid=\"$paperID\")  paper_author_affiliation
                WHERE paper_author_affiliation.authorid=authors.authorid
                ORDER BY paper_author_affiliation.authorsequence ASC;";
            $queryResultForAuthors=$conn->query($queryForAuthors);
            while($authorInfo=$queryResultForAuthors->fetch_assoc()){
                $subAuthorName=$authorInfo["AuthorName"];
                $subAuthorID=$authorInfo["AuthorID"];
                $authorSequence=$authorInfo["AuthorSequence"];
                echo "<li><a href=\"author.php?authorid=$subAuthorID\">".ucwords($subAuthorName)."</a></li>";
            }
            echo "  </ol>
                </td>";
        }
        if(isset($_GET["authorid"])){
            $authorID=filter_input(INPUT_GET, "authorid",FILTER_SANITIZE_MAGIC_QUOTES);
            include_once("connect.php");
            $queryForName="SELECT AuthorName From authors where AuthorID=\"$authorID\"";
            $queryResultForName=$conn->query($queryForName);
            $authorName=($queryResultForName->fetch_assoc())["AuthorName"];
            echo "<h1>".ucwords($authorName)."'s Page</h1>";
            echo "<img id=\"photo\" src=\"img/author.jpg\">";
            echo "<div id=\"profile\"><p>This is the description. This is the description.
            This is the description. This is the description. This is the description. This is the description.
            This is the description. This is the description. </p><p>This is the description. This is the description.
            This is the description. This is the description. This is the description. This is the description.
            This is the description. This is the description. </p></div>";
            $queryForExistence="SELECT count(1) as num FROM paper_author_affiliation WHERE authorid=\"$authorID\"";
            $queryResultForExistence=$conn->query($queryForExistence);
            $row=$queryResultForExistence->fetch_assoc();
            if($row["num"]==0){
                echo "<div id='noResult'>No Paper Founded!</div>";
            }else{
                echo "<table id='papers' align='center'>";
                echo "<th id=\"narrowedColumn\">Paper Title</th>
                    <th>Publish Year</th>
                    <th>Conference</th><th>Citations</th><th style=\"text-align:center\">Author(s)</th>";
                $queryForPaper="
                SELECT papers.*,conferences.ConferenceName,paper_reference.citation
                FROM papers,conferences,paper_author_affiliation,
                    (SELECT referenceid,count(1) AS citation FROM paper_reference GROUP BY referenceid)paper_reference
                WHERE papers.paperid=paper_reference.referenceid
                    AND conferences.conferenceid=papers.conferenceid
                    AND papers.paperid=paper_author_affiliation.paperid
                    AND paper_author_affiliation.authorid=\"$authorID\"
                ORDER BY citation DESC
                LIMIT 10;";
                $paperCnt=0;
                $queryResultForPaper=$conn->query($queryForPaper);
                while($row=$queryResultForPaper->fetch_assoc()){
                    $paperCnt++;
                    handleOnePaper($row,$conn);
                }
                if($paperCnt<10){
                    $extraNum=10-$paperCnt;
                    $queryForExtraPaper="SELECT papers.*,conferences.ConferenceName
                                        FROM papers,conferences,paper_author_affiliation
                                        WHERE papers.paperid=paper_author_affiliation.paperid AND paper_author_affiliation.authorid=\"$authorID\" AND
                                            (Select count(1) FROM paper_reference WHERE paper_reference.referenceid=papers.paperid)=0 AND
                                            papers.conferenceid=conferences.conferenceid
                                        LIMIT $extraNum;";
                    $queryResultForExtra=$conn->query($queryForExtraPaper);
                    while($row=$queryResultForExtra->fetch_assoc()){
                        $paperCnt++;
                        handleOnePaper($row,$conn,$noCitation=true);
                    }
                }
                echo "</table>";
            }
        }
     ?>

</body>
</html>

