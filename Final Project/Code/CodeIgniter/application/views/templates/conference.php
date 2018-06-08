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
			<a href="#basicInfo" name="basicInfo">
				Basic Information
			</a>
		</div>
		<div id="conferenceBasicInfo" class="textContainer">
			Papers:
			<?=$basicInfo["paperNum"]?>
			<br>
			Authors:
			<?=$basicInfo["authorNum"]?>
			<br>
			Influence:
			<?=$basicInfo["influence"]?>
		</div>
		<div class="titleOfContainer">
			<a href="#conferenceDescription" name="conferenceDescription">
				Description
			</a>
		</div>
		<div id="conferenceDescription" class="textContainer">
			Lorem ipsum dolor sit amet, consectetur adipisicing elit. 
			Obcaecati possimus nihil suscipit saepe debitis illo natus incidunt 
			aperiam rerum inventore quaerat ipsum dolore quia, 
			aut, doloribus veritatis qui repellat optio.
		</div>
		<div class="titleOfContainer">
			<a href="#topAuthors" name="topAuthors">
				Top Authors
			</a>
		</div>
		<div id="topAuthors" class="listContainer">
			<ul>
				<? foreach ($topAuthors as $author): ?>
					<li>
						<a href="/author/<?=$author["authorID"]?>">
							<?=$author["authorName"]?>
						</a>
						<span class="times">[<?=$author["papersNum"]?>][<?=$author["allPapersNum"]?>]</span>
					</li>
				<? endforeach ?>
			</ul>
		</div>
	</div>
	<div class="rightContainer">
		<div id="topPapersOfConference">
			<div class="titleOfContainer">
				<a href="#topPapers" name="topPapers">
					Top Papers
				</a>
			</div>
			<div class="overallResult">
				<p><?print_in_correct_form($basicInfo["paperNum"],"paper")?> found.</p>
			</div>
			<table id="topPapersOfConferenceTable" class="resultTable">
				<tr>
					<th class="conferencePaperTitle">Paper</th>
					<th class="conferencePaperYear">Year</th>
					<th class="conferencePaperCitations">Citations</th>
					<th class="conferencePaperAuthors">Authors</th>
				</tr>
				<?php foreach ($topPapersOfConference as $paper): ?>
				<tr class="dataRow">
					<td class="conferencePaperTitle">
						<a href="/paper/<?=$paper["paperID"]?>">
							<?=$paper["title"]?>
						</a>
					</td>
					<td class="conferencePaperYear">
						<?=$paper["paperPublishYear"]?>
					</td>
					<td class="conferencePaperCitations">
						<?=$paper["citations"]?>
					</td>
					<td class="conferencePaperAuthors">
						<ol>
							<?php foreach ($paper["authors"] as $author): ?>
								<li>
									<a href="/author/<?=$author["authorID"]?>">
										<?=$author["authorName"]?>
									</a>
								</li>
							<?php endforeach ?>
						</ol>
					</td>
				</tr>
				<?php endforeach ?>
			</table>
			<div class="pagination">
				<button type="button" id="topPapersOfConferenceResultPrev" class="resultPrev" disabled="disabled">Previous</button>
				<span class="pageInfo">Page
					<span id="topPapersOfConferenceCurrentPage" class="currentPage">
						<?=$topPapersOfConferenceCurrentPage?>
					</span> of
					<?=$topPapersOfConferenceMaxPage?>
				</span>
				<button type="button" id="topPapersOfConferenceResultNext" class="resultNext">
					Next
				</button>		
			</div>
		</div>
	</div>
</div>
