<?php
if($queryResult){
    foreach($queryResult as $row ){
        $resultArray[] = array('id' => $row["AuthorID"],'label'=>$row["AuthorName"] );
    }
    echo json_encode($resultArray);
}

?>
