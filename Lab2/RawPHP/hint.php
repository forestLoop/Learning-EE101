<?php
    if(filter_has_var(INPUT_GET, "term")){
        include_once("connect.php");
        $authorname=filter_input(INPUT_GET, "term",FILTER_SANITIZE_MAGIC_QUOTES);
        $authorname=strtolower($authorname);
        $query=
        "SELECT authors.*,paper_author_affiliation.num
        FROM authors,
        (SELECT authorid,count(1) AS num FROM paper_author_affiliation GROUP BY authorid)paper_author_affiliation
        WHERE authors.authorid=paper_author_affiliation.authorid AND authors.authorname LIKE '%$authorname%'
        ORDER BY num DESC
        limit 10;";
        $queryResult=$conn->query($query);
        while($row = $queryResult->fetch_assoc()) {
            $resultArray[] = array('id' => $row["AuthorID"],'label'=>$row["AuthorName"] );
        }
        echo json_encode($resultArray);
    }

 ?>



