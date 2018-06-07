$(function(){

/*
var svg = d3.select("svg"),
    width = +svg.attr("width"),
    height = +svg.attr("height");
    */
var svg = d3.select("#forceGraph")
            .attr("preserveAspectRatio", "xMidYMid meet")
            .attr("viewBox", "0 0 600 300")
//console.log(width,height);

//var color = d3.scaleOrdinal(d3.schemeCategory20);
//var color=d3.scaleOrdinal(['#25c6fc','#e03636','#edd0be','#5d9431'])
var width=600,height=300;
function color(d)
{
  return ['#25c6fc','#e03636','#edd0be','#5d9431'][d]
}

var simulation = d3.forceSimulation()
    .force("link", d3.forceLink().id(function(d) { return d.id; }))
    .force("charge", d3.forceManyBody())
    .force("center", d3.forceCenter(width / 2, height / 2));

var api="/api/graph/author-relation";
var jsonSource=[api,authorID].join("/");
console.log(jsonSource);

d3.json(jsonSource, function(error, graph) {
  if (error) throw error;
  //spinner.stop();
  var link = svg.append("g")
      .attr("class", "links")
    .selectAll("line")
    .data(graph.links)
    .enter().append("line")
      .attr("stroke-width", function(d) { return Math.sqrt(d.value); });

  var node = svg.append("g")
      .attr("class", "nodes")
    .selectAll("g")
    .data(graph.nodes)
    .enter().append("g")
        .on("mouseover", mouseover)
    .on("mouseout", mouseout)
    
  var circles = node.append("circle")
      .attr("r", 5)
      .attr("fill", function(d) { return color(d.group); })
      .call(d3.drag()
          .on("start", dragstarted)
          .on("drag", dragged)
          .on("end", dragended));

  var lables = node.append("text")
      .text(function(d) {
        return d.authorName;
      })
      .attr('x', 6)
      .attr('y', 3)
      .attr("class","hideMe");

  node.append("title")
      .text(function(d) { return d.id; });

  simulation
      .nodes(graph.nodes)
      .on("tick", ticked);

  simulation.force("link")
      .links(graph.links);

  function ticked() {
    link
        .attr("x1", function(d) { return d.source.x; })
        .attr("y1", function(d) { return d.source.y; })
        .attr("x2", function(d) { return d.target.x; })
        .attr("y2", function(d) { return d.target.y; });

    node
        .attr("transform", function(d) {
          return "translate(" + d.x + "," + d.y + ")";
        })
  }
});

function dragstarted(d) {
  if (!d3.event.active) simulation.alphaTarget(0.3).restart();
  d.fx = d.x;
  d.fy = d.y;
}

function dragged(d) {
  d.fx = d3.event.x;
  d.fy = d3.event.y;
}

function dragended(d) {
  if (!d3.event.active) simulation.alphaTarget(0);
  d.fx = null;
  d.fy = null;
}

function mouseover() {
  d3.select(this).select("text").attr("style","display:inline");
    d3.select(this).select("circle").transition()
      .duration(750)
      .attr("r", 10);
}

function mouseout() {
  d3.select(this).select("text").attr("style","display:none");
    d3.select(this).select("circle").transition()
      .duration(750)
      .attr("r", 5);
}
});
