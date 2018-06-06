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
		<img id="authorPhoto" src="<?php echo $author_info['authorImg']; ?>">
		<div class="titleOfContainer">
			<a href="#relatedAffiliations" name="relatedAffiliations">Affiliation(s)</a>
		</div>
		<div id="authorRelatedAffiliations">
			<ul>
			<?foreach($relatedAffiliations as $affiliation):?>
			<li>
			<a href="/affiliation/<?=$affiliation["affiliationID"]?>">
				<?=ucwords($affiliation["affiliationName"])?>
			</a>
			<span class="times">[<?=$affiliation["num"]?>]</span>
			</li>
			<?endforeach;?>
			</ul>
		</div>

		<div class="titleOfContainer">
			<a href="#relatedConferences" name="relatedConferences">Conference(s)</a>
		</div>
		<div id="authorRelatedConferences">
			<ul>
			<?foreach($relatedConferences as $conference):?>
			<li>
			<a href="/conference/<?=$conference["conferenceID"]?>">
				<?=ucwords($conference["conferenceName"])?>
			</a>
			<span class="times">[<?=$conference["num"]?>]</span>
			</li>
			<?endforeach;?>
			</ul>
		</div>

		<div class="titleOfContainer">
			<a href="#description" name="description">Description</a>
		</div>
		<div id="authorDescription">
			<?=$author_info["authorDescription"]?>
		</div>

		<div class="titleOfContainer">
			<a href="#relatedAuthors" name="relatedAuthors">Author(s)</a>
		</div>
		<div id="authorRelatedAuthors">
			<ul>
			<?foreach($relatedAuthors as $author):?>
			<li>
			<a href="/author/<?=$author["authorID"]?>">
				<?=$author["authorName"]?>
			</a>
			<span class="times">[<?=$author["cooperationTimes"]?>]</span>
			</li>
			<?endforeach;?>
			</ul>
		</div>

    	<div class="titleOfContainer">
    		<a href="#tagCloud" name="tagCloud">
    			Tag Cloud
    		</a>
    	</div>
    	<div id="tagCloud">
    		<script src="/static/js/jquery.svg3dtagcloud.js"></script>
    		<script>
    			$(function()
				{
					myTagCloud("#tagCloud","/api/graph/author-tagcloud/"+authorID);
				})
    		</script>
    	</div>
	</div>

	<div class="rightContainer">
    	<div id="papers">
    		<div class="titleOfContainer">
    			<a href="#papers" name="papers">Paper(s)</a>
    		</div>
    		<div class="resultContainer">
    			<div class="overallResult">
    				<p><?print_in_correct_form($paperNum,"paper")?> found.</p>
    			</div> 				
    			<table id="authorPapersTable" class="resultTable">
        			<tr>
            			<th class="authorPaperTitle">Paper Title</th>
            			<th class="authorPaperPublishYear">Year</th>
            			<th class="authorPaperConference">Conference</th>
            			<th class="authorPaperCitations">Citations</th>
            			<th class="authorPaperAuthors">Author(s)</th>
					</tr>
					<? foreach($author_info["papers"] as $paper):?>
					<tr class="dataRow">
						<td class="authorPaperTitle">
							<a href="/paper/<?=$paper["paperID"]?>"><?=$paper["paperTitle"]?></a>
						</td>
						<td class="authorPaperPublishYear">
							<?=$paper["paperPublishYear"]?>
						</td>
						<td class="authorPaperConference">
							<a href="/conference/<?=$paper["conferenceID"]?>"><?=$paper["conferenceName"]?></a>
						</td>
						<td class="authorPaperCitations">
							<?=$paper["citation"]?>
						</td>
						<td class="authorPaperAuthors">
							<ol>
								<?foreach($paper["authors"] as $author):?>
								<li>
									<a href="/author/<?=$author["subAuthorID"]?>">
										<?=$author["subAuthorName"]?>
									</a>
								</li>
								<?endforeach;?>
							</ol>
						</td>
					</tr>
					<? endforeach;?>
				</table>
    			<div class="pagination">
        			<button type="button" id="authorPageResultPrev" class="resultPrev" disabled="disabled">Previous</button>
        			<span class="pageInfo">Page <span id="authorPageCurrentPage" class="currentPage"><?=$currentPage?></span> of <?=$maxPage?></span>
        			<button type="button" id="authorPageResultNext" class="resultNext">Next</button>
    			</div>
    		</div>
    	</div>
    	<div id="relationGraph">
    		<div class="titleOfContainer">
    			<a href="#relationGraph" name="relationGraph">
    				Relation Graph
    			</a>
    		</div>
    		<div class="svg-container">
    			<svg class="svg-content" id="forceGraph">
    				<script src="/static/js/relation-graph.js"></script>
    			</svg>
    		</div>
    	</div>
    	<div id="activityGraph">
    		<div class="titleOfContainer">
    			<a href="#activityGraph" name="activityGraph">
    				Activity Graph
    			</a>
    		</div>
    		<div class="svg-container">
    			<script src="/static/js/author-activity-graph.js"></script>
    			<svg class="svg-content" id="authorActivityGraph">
    			</svg>
    		</div>
    	</div>
	</div>
</div>
