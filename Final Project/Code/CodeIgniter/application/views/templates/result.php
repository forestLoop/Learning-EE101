<?php
    function echoAuthor($authorID,$authorName,$paperNum,$affiliationID,$affiliationName)
    {
        $affiliationName=$affiliationName ?ucwords($affiliationName): "None";
        $affiliationID=$affiliationID??"00000000";
        $authorName=ucwords($authorName);
        echo "    <tr class='dataRow'>
        <td class='AuthorName'><a href='/author/$authorID'>$authorName</a></td>
        <td class='PaperNum'>$paperNum</td>
        <td class='AffiliationName'><ul><li><a href='/affiliation/$affiliationID'>$affiliationName</a></li></ul></td>
    </tr>\n";
    }
?>


<div class="searchResult">
	<div class="overallResult"><p><?php echo "$resultNum results have been found." ?></p></div>

<?php if(isset($authorNum)):?>
	<div class="resultContainer">
	    <div class="overallResult">
	    	<p><?php echo "$authorNum authors have been found." ?></p>
	    </div>
		<?php if($authorNum!=0):?>
	    <table class='resultTable'>
	        <tr>
	            <th class="AuthorName">Author Name</th>
	            <th class="PaperNum">Papers</th>
	            <th class="AffiliationName">Affiliation Name</th>
	        </tr>
	        <?php
	        foreach ($authorResult as $singleAuthor) {
	            echoAuthor($singleAuthor["authorID"],$singleAuthor["authorName"],$singleAuthor["paperNum"],
	            	$singleAuthor["affiliationID"],$singleAuthor["affiliationName"]);
	          }
	        ?>
	    </table>
	    <div id="pagination">
	        <button type="button" id="resultPrev" disabled="disabled">Previous</button>
	        <span id="pageInfo">Page <span id="currentPage"><?php echo 1?></span> of 
	        	<?php echo 10 ?></span>
	        <button type="button" id="resultNext">Next</button>
	    </div>
	    <?php endif;?>
	</div>
<?php endif;?>


<?php if(isset($paperNum)):?>
	<div class="resultContainer">
	    <div class="overallResult">
	    	<p><?=$paperNum?> papers have been found.</p>
	    </div>
		<?php if($paperNum!=0) :?>
	    <table class="resultTable">
	    	<tr>
	    		<th>Paper Title</th>
	    		<th>Publish Year</th>
	    		<th>Conference</th>
	    		<th>Citations</th>
	    		<th>Author(s)</th>
	    	</tr>
			<?php foreach($paperResult as $singlePaper):?>
			<tr>
				<td><a href="/paper/<?=$singlePaper['paperID']?>"><?=$singlePaper["title"]?></a></td>
				<td><?=$singlePaper["paperPublishYear"]?></td>
				<td><a href="/conference/<?=$singlePaper['conferenceID']?>"><?$singlePaper["conferenceName"]?></a></td>
				<td><?$singlePaper["citations"]?></td>
				<td>
					<ol>
							<?php foreach($singlePaper["authors"] as $author):?>
							<li>
								<a href="/author/<?=$author['authorID']?>">
									<?=$author["authorName"]?>
								</a>
							</li>
							<?php endforeach;?>
					</ol>
				</td>
			</tr>
			<?php endforeach;?>
	    </table>
		<?php endif;?>
	</div>
<?php endif;?>

<?php if(isset($conferenceNum)):?>
	<div class="resultContainer">
		<div class="overallResult">
			<p><?=$conferenceNum?> conferences are found.</p>
		</div>
		<?php if($conferenceNum!=0):?>
		<table class="resultTable">
			<tr>
				<th>Conference Name</th>
				<th>Papers</th>
				<th>Authors</th>
				<th>Influence</th>
			</tr>
			<?php foreach($conferenceResult as $singleConference) :?>
			<tr>
				<td>
					<a href="/conference/<?=$singleConference['conferenceID']?>">
						<?=$singleConference["conferenceName"]?>
					</a>
				</td>
				<td><?=$singleConference["paperNum"]?></td>
				<td><?=$singleConference["authorNum"]?></td>
				<td><?=$singleConference["influence"]?></td>
			</tr>
			<?php endforeach;?>
		</table>
		<?php endif;?>
	</div>
<?php endif;?>

<?php if(isset($affiliationNum)):?>
	<div class="resultContainer">
		<div class="overallResult">
			<p><?=$affiliationNum?> affiliations are found.</p>
		</div>
		<?php if($affiliationNum!=0):?>
		<table class="resultTable">
			<tr>
				<th>Affiliation Name</th>
				<th>Papers</th>
				<th>Authors</th>
				<th>Influence</th>
			</tr>
			<?php foreach($affiliationResult as $singleAffiliation) :?>
			<tr>
				<td>
					<a href="/affiliation/<?=$singleAffiliation['affiliationID']?>">
						<?=$singleAffiliation["affiliationName"]?>
					</a>
				</td>
				<td><?=$singleAffiliation["paperNum"]?></td>
				<td><?=$singleAffiliation["authorNum"]?></td>
				<td><?=$singleAffiliation["influence"]?></td>
			</tr>
			<?php endforeach;?>
		</table>
		<?php endif;?>
	</div>
<?php endif;?>



</div>
