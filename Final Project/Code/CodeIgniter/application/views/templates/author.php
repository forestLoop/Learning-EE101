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
		<div id="authorDescription">
			<?=$author_info["authorDescription"]?>
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
        			<button type="button" id="papersPrev" class="resultPrev" disabled="disabled">Previous</button>
        			<span class="pageInfo">Page <span id="authorPageCurrentPage" class="currentPage"><?=$currentPage?></span> of <?=$maxPage?></span>
        			<button type="button" id="papersNext" class="resultNext">Next</button>
    			</div>
    		</div>
    	</div>
	</div>
</div>
