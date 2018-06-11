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
		<div id="affiliationBasicInfo" class="textContainer">
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
			<a href="#description" name="description">
				Description
			</a>
		</div>
		<div id="affiliationDescription" class="textContainer">
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
						<span class="times">[<?=$author["influence"]?>]</span>
					</li>
				<? endforeach ?>
			</ul>
		</div>
	</div>
	<div class="rightContainer">
		<div id="topPapersOfAffiliation">
			<div class="titleOfContainer">
				<a href="#topPapers" name="topPapers">
					Top Papers
				</a>
			</div>
			<div class="resultContainer">
				<div class="overallResult">
					<p><?print_in_correct_form($basicInfo["paperNum"],"paper")?> found.</p>
				</div>
				<table id="topPapersOfAffiliationTable" class="resultTable">
					<tr>
						<th class="affiliationPaperTitle">Paper</th>
						<th class="affiliationPaperYear">Year</th>
						<th class="affiliationPaperConference">Conference</th>
						<th class="affiliationPaperCitations">Citations</th>
						<th class="affiliationPaperAuthors">Authors</th>
					</tr>
					<?php foreach ($topPapersOfAffiliation as $paper): ?>
					<tr class="dataRow">
						<td class="affiliationPaperTitle">
							<a href="/paper/<?=$paper["paperID"]?>">
								<?=$paper["title"]?>
							</a>
						</td>
						<td class="affiliationPaperYear">
							<?=$paper["paperPublishYear"]?>
						</td>
						<td class="affiliationPaperConference">
							<a href="/conference/<?=$paper["conferenceID"]?>">
								<?=$paper["conferenceName"]?>
							</a>
						</td>
						<td class="affiliationPaperCitations">
							<?=$paper["citations"]?>
						</td>
						<td class="affiliationPaperAuthors">
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
					<button type="button" id="topPapersOfAffiliationResultPrev" class="resultPrev" disabled="disabled">Previous</button>
					<span class="pageInfo">Page
						<span id="topPapersOfAffiliationCurrentPage" class="currentPage">
							<?=$topPapersOfAffiliationCurrentPage?>
						</span> of
						<?=$topPapersOfAffiliationMaxPage?>
					</span>
					<button type="button" id="topPapersOfAffiliationResultNext" class="resultNext">
						Next
					</button>		
				</div>
			</div>			
		</div>
		<div id="affiliationPapersYearly">
			<div class="titleOfContainer">
				<a href="#papersYearlyGraph" name="papersYearlyGraph">
					Papers Graph
				</a>
			</div>
			<div class="svg-container">
				<script src="/static/js/affiliation-papers-graph.js"></script>
				<svg class="svg-content" id="papersYearlyGraph"></svg>
			</div>
		</div>
	</div>
</div>
