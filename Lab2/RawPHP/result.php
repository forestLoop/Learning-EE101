<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <?php
        function echoAuthor($authorID,$authorName,$paperNum,$affiliationID,$affiliationName)
        {
            $affiliationName=$affiliationName ?ucwords($affiliationName): "None";
            $affiliationID=$affiliationID??"00000000";
            $authorName=ucwords($authorName);
            echo "<tr>
                    <td ><a href='author.php?authorid=$authorID'>$authorName</a></td>
                    <td id=\"widenColumn\">$paperNum</td>
                    <td ><ul><li><a href='affiliation.php?affiliationid=$affiliationID'>$affiliationName</a></li></ul></td>
                </tr>";

        }
        if(isset($_GET['authorname'])){
            $authorname=filter_input(INPUT_GET, "authorname",FILTER_SANITIZE_MAGIC_QUOTES);
            $authorname=strtolower($authorname);
            echo "<h1>Result of ".ucwords($authorname)."</h1>";
            include_once("connect.php");
            $queryForAuthor=
            "SELECT authors.*,paper_author_affiliation.num
            FROM authors,
            (SELECT authorid,count(1) AS num FROM paper_author_affiliation GROUP BY authorid)paper_author_affiliation
            WHERE authors.authorid=paper_author_affiliation.authorid AND authors.authorname LIKE '%$authorname%'
            ORDER BY num DESC
            limit 10;";
            $queryResultForAuthor=$conn->query($queryForAuthor);
            $row=$queryResultForAuthor->fetch_assoc();
            if(!$row)
                echo "<div id='noResult'>No Author Found!</div>";
            else{
                echo "<table id='searchResult' align='center'>";
                echo "<th>Author Name</th><th id=\"widenColumn\">Total Citations</th>
                    <th>Affiliation Name</th>";
                do{
                    $authorID=$row["AuthorID"];
                    $authorName=$row["AuthorName"];
                    $paperNum=$row["num"];
                    $queryForAffiliation=
                    "SELECT affiliations.*
                    FROM affiliations,
                        (SELECT affiliationid,count(1)
                        FROM paper_author_affiliation
                        WHERE authorid='$authorID'
                        GROUP BY affiliationid
                        ORDER BY count(1) DESC)paper_author_affiliation
                    WHERE affiliations.affiliationid=paper_author_affiliation.affiliationid
                    LIMIT 1;";
                    $queryResultForAffiliation=$conn->query($queryForAffiliation);
                    $rowAff=$queryResultForAffiliation->fetch_assoc();
                    //print_r($rowAff);
                    $affiliationID=$rowAff["AffiliationID"];
                    $affiliationName=$rowAff["AffiliationName"];
                    echoAuthor($authorID,$authorName,$paperNum,$affiliationID,$affiliationName);
                }while($row=$queryResultForAuthor->fetch_assoc());
                echo "</table>";
            }
        }else{
            echo "<div id='noResult'>Invalid Author Name!</div>";
        }
     ?>
</body>
</html>
