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

<div class="mainContainer">
	<div class="leftContainer">
		<div class="titleOfContainer">
			<a href="#basicInfo" name="basicInfo">Basic Information</a>
		</div>
		<div id="paperBasicInfo" >
			Publish Year:
			<?=$paperInfo["paperPublishYear"]?>
			<br>
			Conference:
			<a href="/conference/<?=$paperInfo["conferenceID"]?>">
				<?=$paperInfo["conferenceName"]?>
			</a>
			<br>
			Citations:
			<?=$paperInfo["citations"]?>
		</div>
		<div class="titleOfContainer">
			<a href="#authors" name="authors">Authors</a>
		</div>
		<div id="paperPageAuthors">
			<ol>
				<?foreach($paperInfo["authors"] as $author):?>
				<li>
					<a href="/author/<?=$author["authorID"]?>">
						<?=$author["authorName"]?>
					</a>
				</li>
				<?endforeach;?>
			</ol>
		</div>
	</div>
	<div class="rightContainer">
		<div class="titleOfContainer">
			<a href="#papersCitedByThis" name="papersCitedByThis">Paper(s) Cited By This Paper</a>
		</div>
		<div id="papersCitedByThis" class="resultContainer">
			<div class="overallResult">
				<p><?print_in_correct_form($papersCitedByThisNum,"paper")?> found.</p>
			</div>
			<table id="papersCitedByThisTable" class="resultTable">
				<tr>
					<th class="paperPageTitle">Title</th>
					<th class="paperPageYear">Year</th>
					<th class="paperPageConference">Conference</th>
					<th class="paperPageCitations">Citations</th>
				</tr>
				<?foreach($papersCitedByThis as $paper):?>
				<tr class="dataRow">
					<td class="paperPageTitle">
						<a href="/paper/<?=$paper["paperID"]?>">
							<?=$paper["title"]?>
						</a>
					</td>
					<td class="paperPageYear">
						<?=$paper["paperPublishYear"]?>
					</td>
					<td class="paperPageConference">
						<a href="/conference/<?=$paper["conferenceID"]?>">
							<?=$paper["conferenceName"]?>
						</a>
					</td>
					<td class="paperPageCitations">
						<?=$paper["citations"]?>
					</td>
				</tr>
				<?endforeach;?>
			</table>
    		<div class="pagination">
        		<button type="button" id="papersCitedByThisResultPrev" class="resultPrev" disabled="disabled">Previous</button>
        		<span class="pageInfo">Page <span id="papersCitedByThisCurrentPage" class="currentPage"><?=$papersCitedByThisCurrentPage?></span> of <?=$papersCitedByThisMaxPage?></span>
        		<button type="button" id="papersCitedByThisResultNext" class="resultNext">Next</button>
    		</div>
		</div>
		<div class="titleOfContainer">
			<a href="#papersCitingThis" name="papersCitingThis">
				Papers(s) Citing This Paper
			</a>
		</div>
		<div id="papersCitingThis" class="resultContainer">
			<div class="overallResult">
				<p><?print_in_correct_form($papersCitingThisNum,"paper")?> found.</p>
			</div>
			<table id="papersCitingThisTable" class="resultTable">
				<tr>
					<th class="paperPageTitle">Title</th>
					<th class="paperPageYear">Year</th>
					<th class="paperPageConference">Conference</th>
					<th class="paperPageCitations">Citations</th>
				</tr>
				<?foreach($papersCitingThis as $paper):?>
				<tr class="dataRow">
					<td class="paperPageTitle">
						<a href="/paper/<?=$paper["paperID"]?>">
							<?=$paper["title"]?>
						</a>
					</td>
					<td class="paperPageYear">
						<?=$paper["paperPublishYear"]?>
					</td>
					<td class="paperPageConference">
						<a href="/conference/<?=$paper["conferenceID"]?>">
							<?=$paper["conferenceName"]?>
						</a>
					</td>
					<td class="paperPageCitations">
						<?=$paper["citations"]?>
					</td>
				</tr>
				<?endforeach;?>
			</table>
			<div class="pagination">
        		<button type="button" id="papersCitingThisResultPrev" class="resultPrev" disabled="disabled">Previous</button>
        		<span class="pageInfo">Page <span id="papersCitingThisCurrentPage" class="currentPage"><?=$papersCitingThisCurrentPage?></span> of <?=$papersCitingThisMaxPage?></span>
        		<button type="button" id="papersCitingThisResultNext" class="resultNext">Next</button>
    		</div>
		</div>
	</div>
</div>
