<?php 
	function print_in_correct_form($number,$noun)
	{
		if($number>=2){
			$noun=$noun."s";
			$verb="are";
		}else{
			$verb="is";
		}
		echo "$number $noun $verb";
	}

?>


<div class="searchResult">
	<div class="overallResult">
		<p><?print_in_correct_form($resultNum,"result")?> found.</p>
		<ul>
			<li>Quick Jump:</li>
		<?php
		if(isset($authorNum))
			echo '<li><a href="#authorResult">Author</a></li>';
		if(isset($paperNum))
			echo '<li><a href="#paperResult">Paper</a></li>';
		if(isset($conferenceNum))
			echo '<li><a href="#conferenceResult">Conference</a></li>';
		if(isset($affiliationNum))
			echo '<li><a href="#affiliationResult">Affiliation</a></li>';
		?>
		</ul>
	</div>

<?php if(isset($authorNum)):?>
	<div class="titleOfContainer">
		<a href="#authorResult" name="authorResult">Author(s)</a>
	</div>
	<div class="resultContainer">
	    <div class="overallResult">
	    	<p><?print_in_correct_form($authorNum,"author")?> found.</p>
	    </div>
		<?php if($authorNum!=0):?>
	    <table class='resultTable' id='authorResultTable'>
	        <tr>
	            <th class="AuthorName">Author Name</th>
	            <th class="PaperNum">Papers</th>
	            <th class="AffiliationName">Affiliation Name</th>
	        </tr>
	        <?php foreach($authorResult as $singleAuthor):?>
	        <tr class="dataRow">
	        	<td class="AuthorName">
	        		<a href="/author/<?=$singleAuthor['authorID']?>">
	        			<?=$singleAuthor["authorName"]?>
	        		</a>
	        	</td>
	        	<td class="PaperNum"><?=$singleAuthor["paperNum"]?></td>
	        	<td class="AffiliationName">
	        		<ul>
	        			<li>
	        				<a href="/affiliation/<?=$singleAuthor['affiliationID']?>">
	        					<?=$singleAuthor["affiliationName"]?>
	        				</a>
	        			</li>
	        		</ul>
	        	</td>
	        </tr>
	        <?php endforeach;?>
	    </table>
	    <div class="pagination">
	        <button type="button" id="authorResultPrev" class="resultPrev" disabled="disabled">Previous</button>
	        <span class="pageInfo">Page <span id="authorCurrentPage" class="currentPage">1</span> of 
	        	<?=$authorMaxPage?></span>
	        <button type="button" id="authorResultNext" class="resultNext">Next</button>
	    </div>
	    <?php endif;?>
	</div>
<?php endif;?>


<?php if(isset($paperNum)):?>
	<div class="titleOfContainer">
		<a href="#paperResult" name="paperResult">Paper(s)</a>
	</div>
	<div class="resultContainer">
	    <div class="overallResult">
	    	<p><?print_in_correct_form($paperNum,"paper")?> found.</p>
	    </div>
		<?php if($paperNum!=0) :?>
	    <table class="resultTable" id="paperResultTable">
	    	<tr>
	    		<th class="paperTitle">Paper Title</th>
	    		<th class="paperPublishYear">Publish Year</th>
	    		<th class="paperConference">Conference</th>
	    		<th class="paperCitations">Citations</th>
	    		<th class="paperAuthors">Author(s)</th>
	    	</tr>
			<?php foreach($paperResult as $singlePaper):?>
			<tr class="dataRow">
				<td class="paperTitle"><a href="/paper/<?=$singlePaper['paperID']?>"><?=$singlePaper["title"]?></a></td>
				<td class="paperPublishYear"><?=$singlePaper["paperPublishYear"]?></td>
				<td class="paperConference"><a href="/conference/<?=$singlePaper['conferenceID']?>"><?=$singlePaper["conferenceName"]?></a></td>
				<td class="paperCitations"><?=$singlePaper["citations"]?></td>
				<td class="paperAuthors">
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
	   	<div class="pagination">
	        <button type="button" id="paperResultPrev" class="resultPrev" disabled="disabled">Previous</button>
	        <span class="pageInfo">Page <span id="paperCurrentPage" class="currentPage">1</span> of 
	        	<?=$paperMaxPage?></span>
	        <button type="button" id="paperResultNext" class="resultNext">Next</button>
	    </div>
		<?php endif;?>
	</div>
<?php endif;?>

<?php if(isset($conferenceNum)):?>
	<div class="titleOfContainer">
		<a href="#conferenceResult" name="conferenceResult">Conference(s)</a>
	</div>
	<div class="resultContainer">
		<div class="overallResult">
			<p><?print_in_correct_form($conferenceNum,"conference")?> found.</p>
		</div>
		<?php if($conferenceNum!=0):?>
		<table class="resultTable" id="conferenceResultTable">
			<tr>
				<th class="conferenceName">Conference Name</th>
				<th class="conferencePaperNum">Papers</th>
				<th class="conferenceAuthorNum">Authors</th>
				<th class="conferenceInfluence">Influence</th>
			</tr>
			<?php foreach($conferenceResult as $singleConference) :?>
			<tr class="dataRow">
				<td class="conferenceName">
					<a href="/conference/<?=$singleConference['conferenceID']?>">
						<?=$singleConference["conferenceName"]?>
					</a>
				</td>
				<td class="conferencePaperNum"><?=$singleConference["paperNum"]?></td>
				<td class="conferenceAuthorNum"><?=$singleConference["authorNum"]?></td>
				<td class="conferenceInfluence"><?=$singleConference["influence"]?></td>
			</tr>
			<?php endforeach;?>
		</table>
	    <div class="pagination">
	        <button type="button" id="conferenceResultPrev" class="resultPrev" disabled="disabled">Previous</button>
	        <span class="pageInfo">Page <span id="conferenceCurrentPage" class="currentPage">1</span> of 
	        	<?=$conferenceMaxPage?></span>
	        <button type="button" id="conferenceResultNext" class="resultNext">Next</button>
	    </div>
		<?php endif;?>
	</div>
<?php endif;?>

<?php if(isset($affiliationNum)):?>
	<div class="titleOfContainer">
		<a href="#affiliationResult" name="affiliationResult">Affiliation(s)</a>
	</div>
	<div class="resultContainer">
		<div class="overallResult">
			<p><?print_in_correct_form($affiliationNum,"affiliation")?> found.</p>
		</div>
		<?php if($affiliationNum!=0):?>
		<table class="resultTable" id="affiliationResultTable">
			<tr>
				<th class="affiliationName">Affiliation Name</th>
				<th class="affiliationPaperNum">Papers</th>
				<th class="affiliationAuthorNum">Authors</th>
				<th class="affiliationInfluence">Influence</th>
			</tr>
			<?php foreach($affiliationResult as $singleAffiliation) :?>
			<tr class="dataRow">
				<td class="affiliationName">
					<a href="/affiliation/<?=$singleAffiliation['affiliationID']?>">
						<?=$singleAffiliation["affiliationName"]?>
					</a>
				</td>
				<td class="affiliationPaperNum"><?=$singleAffiliation["paperNum"]?></td>
				<td class="affiliationAuthorNum"><?=$singleAffiliation["authorNum"]?></td>
				<td class="affiliationInfluence"><?=$singleAffiliation["influence"]?></td>
			</tr>
			<?php endforeach;?>
		</table>
	    <div class="pagination">
	        <button type="button" id="affiliationResultPrev" class="resultPrev" disabled="disabled">Previous</button>
	        <span class="pageInfo">Page <span id="affiliationCurrentPage" class="currentPage">1</span> of 
	        	<?=$affiliationMaxPage?></span>
	        <button type="button" id="affiliationResultNext" class="resultNext">Next</button>
	    </div>
		<?php endif;?>
	</div>
<?php endif;?>


</div>
