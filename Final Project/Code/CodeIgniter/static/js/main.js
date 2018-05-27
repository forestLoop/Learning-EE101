$(function(){
    $( "#homeInput" ).autocomplete({
        source: "hint.php",
        minLength: 1,
    });
});

var currentPage,maxPage,pageSize;
var query;
var apiUrl;


function fillResultTableWithJSON(data)
{
	$(".dataRow").each(function(index,el){
		if(index>=data["itemNum"]){
			$(this).hide();
			console.log(index+"Hidden");
		}else{
			$(this).show();
			$(this).children(".PaperNum").text(data["searchResult"][index]["paperNum"]);
			$(this).children(".AuthorName").children("a").attr("href","/author/"+data["searchResult"][index]["authorID"]);
			$(this).children(".AuthorName").children("a").text(data["searchResult"][index]["authorName"]);
			$(this).children(".AffiliationName").find("a").attr("href","/affiliation/"+data["searchResult"][index]["affiliationID"]);
			$(this).children(".AffiliationName").find("a").text(data["searchResult"][index]["affiliationName"]);
		}
	});
	$("#currentPage").text(currentPage);
}

function fillPapersTableWithJSON(data)
{
	$(".dataRow").each(function(index,el){
		if(index>=data["itemNum"]){
			$(this).hide();
			console.log(index+"Hidden");
		}else{
			$(this).show();
			$(this).children(".PaperTitle").text(data["papers"][index]["paperTitle"]);
			$(this).children(".PublishYear").text(data["papers"][index]["paperPublishYear"]);
			$(this).children(".Conference").text(data["papers"][index]["conferenceName"]);
			$(this).children(".Citations").text(data["papers"][index]["citation"]);
			$(this).children(".Authors").children("ol").empty();
			subAuthors=data["papers"][index]["authors"];
			for(i = 0,len=subAuthors.length; i < len; i++) {
				var content="<a href='/author/"+subAuthors[i]["subAuthorID"]+"'>"+subAuthors[i]["subAuthorName"]+"</a>";
				$(this).children(".Authors").children("ol").append("<li>"+content+"</li>");
   			}
		}
	});
	$("#currentPage").text(currentPage);
}

function checkButtonStatus(prev,next)
{
	$(prev).attr("disabled",currentPage<=1);
	$(next).attr("disabled",currentPage>=maxPage);
}

$(function(){
	$("#resultNext").click(function(){
		if(currentPage<maxPage){
			var nextPage=currentPage+1;
			var targetUrl=[apiUrl,query,nextPage.toString(),pageSize.toString()].join('/');
			console.log(targetUrl);
			$.getJSON(targetUrl,fillResultTableWithJSON);
			currentPage=nextPage;
		}
		checkButtonStatus("#resultPrev","#resultNext");
})
});

$(function(){
	$("#resultPrev").click(function(){
		if(currentPage>1){
			var prevPage=currentPage-1;
			var targetUrl=[apiUrl,query,prevPage.toString(),pageSize.toString()].join('/');
			console.log(targetUrl);
			$.getJSON(targetUrl,fillResultTableWithJSON);
			currentPage=prevPage;
		}
		checkButtonStatus("#resultPrev","#resultNext");
	})
})

$(function(){
	$("#papersPrev").click(function(){
		if(currentPage>1){
			var prevPage=currentPage-1;
			var targetUrl=[apiUrl,query,prevPage.toString(),pageSize.toString()].join('/');
			console.log(targetUrl);
			$.getJSON(targetUrl,fillPapersTableWithJSON);
			currentPage=prevPage;
		}
		checkButtonStatus("#papersPrev","#papersNext");
	})
})

$(function(){
	$("#papersNext").click(function(){
		if(currentPage<maxPage){
			var nextPage=currentPage+1;
			var targetUrl=[apiUrl,query,nextPage.toString(),pageSize.toString()].join('/');
			console.log(targetUrl);
			$.getJSON(targetUrl,fillPapersTableWithJSON);
			currentPage=nextPage;
		}
		checkButtonStatus("#papersPrev","#papersNext");
})
});
