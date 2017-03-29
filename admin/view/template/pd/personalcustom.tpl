<?php echo $header; ?><?php //echo $column_left; ?>
<style>

.node {
  cursor: pointer;
}

.node circle {
  fill: #fff;
  stroke: steelblue;
  stroke-width: 1.5px;
}

.node text {
  font: 16px sans-serif;
}

.link {
  fill: none;
  stroke: #ccc;
  stroke-width: 1.5px;
}
.bitree{margin-left: 12px;
    padding: 0px;
    overflow-x: scroll;
    
   
}
svg:not(:root) {
     overflow: auto; 
         /*margin-left: 100px;*/
}
.bitree::-webkit-scrollbar-track
{
  -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
  background-color: #F5F5F5;
}

.bitree::-webkit-scrollbar
{
  width: 10px;
  background-color: #F5F5F5;
}

.bitree::-webkit-scrollbar-thumb
{
  background-color: #0ae;
  
  background-image: -webkit-gradient(linear, 0 0, 0 100%,
                     color-stop(.5, rgba(255, 255, 255, .2)),
             color-stop(.5, transparent), to(transparent));
}
span.cir {
    border: 5px solid #00f;
    width: 10px;
    border-radius: 50px;
    height: 10px;
    display: inline-block;
}
.cir.lv0{
  border-color: black;
}
.cir.lv1{
  border-color: blue;
}
.cir.lv2{
  border-color: red;
}
.cir.lv3{
  border-color: darkturquoise;
}
.cir.lv4{
  border-color: chartreuse;
}
.cir.lv5{
  border-color: yellow;
}
.cir.lv6{
  border-color: cyan;
}
#header{
  display: none;
}
</style>
<div id="content">

<div class="container-fluid">
  <div class="panel panel-default">
   
    
    <div class="panel-body">
       <div class="navbar-form">
        <div id="search" style="width:340px;"></div>
            <div class="panel-body tab-pane bitree active" id="tab-tree">
        
              
               
        </div> 
        </div>
     
    
  </div>
</div>
<style>
  .node {
    cursor: pointer;
  }

  .node circle {
    fill: #fff;
    stroke: steelblue;
    stroke-width: 1.5px;
  }

  .found {
    fill: #ff4136;
    stroke: #ff4136;
  }
  .node text {
    font: 10px sans-serif;
  }

  .link {
    fill: none;
    stroke: #ccc;
    stroke-width: 1.5px;
  }
  /*Just to ensure the select2 box is "glued" to the top*/
  .search {
    margin-top: 50px;
    width: 20%;
  }
  .select2-container {
    margin: 0;
    margin-left: 200px !important;
    position: relative;
    display: inline-block;
    zoom: 1;
    vertical-align: middle;
}
</style>
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.css"></link>


<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/select2/3.5.0/select2.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/d3/3.4.11/d3.min.js"></script>
<!-- This will be attached to select2, only static element on the page -->


<!-- Main -->
<script type="text/javascript">

  //basically a way to get the path to an object
  function searchTree(obj,search,path){
    if(obj.username === search){ //if search is found return, add the object to the path and return it
      path.push(obj);
      return path;
    }
    else if(obj.children || obj._children){ //if children are collapsed d3 object will have them instantiated as _children
      var children = (obj.children) ? obj.children : obj._children;
      for(var i=0;i<children.length;i++){
        path.push(obj);// we assume this path is the right one
        var found = searchTree(children[i],search,path);
        if(found){// we were right, this should return the bubbled-up path from the first if statement
          return found;
        }
        else{//we were wrong, remove this parent from the path and continue iterating
          path.pop();
        }
      }
    }
    else{//not the right object, return false so it will continue to iterate in the loop
      return false;
    }
  }

  function extract_select2_data(node,leaves,index){
          if (node.children){
              for(var i = 0;i<node.children.length;i++){
                  index = extract_select2_data(node.children[i],leaves,index)[0];
              }
          }
          else {
              leaves.push({id:++index,text:node.username+" | "+node.account_holder});
          }
          return [index,leaves];
  }

  var div = d3.select("#tab-tree")
    .append("div") // declare the tooltip div
    .attr("class", "tooltip")
    .style("opacity", 0);

  var margin = {top: 20, right: 120, bottom: 20, left: 120},
    width = 10000 - margin.right - margin.left,
    height = 800 - margin.top - margin.bottom;

  var i = 0,
    duration = 750,
    root,
    select2_data;

  var diameter = 960;

  var tree = d3.layout.tree()
    .size([height, width]);

  var diagonal = d3.svg.diagonal()
    .projection(function(d) { return [d.y, d.x]; });

  var svg = d3.select("#tab-tree").append("svg")
    .attr("width", width + margin.right + margin.left)
    .attr("height", height + margin.top + margin.bottom)
      .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  //recursively collapse children
  function collapse(d) {
    if (d.children) {
      d._children = d.children;
      d._children.forEach(collapse);
      d.children = null;
    }
  }

  // Toggle children on click.
  function click(d) {
    if (d.children) {
      d._children = d.children;
      d.children = null;
      }
      else{
      d.children = d._children;
      d._children = null;
      }
    update(d);
  }

  function openPaths(paths){
    for(var i =0;i<paths.length;i++){
      if(paths[i].id !== "1"){//i.e. not root
        paths[i].class = 'found';
        if(paths[i]._children){ //if children are hidden: open them, otherwise: don't do anything
          paths[i].children = paths[i]._children;
            paths[i]._children = null;
        }
        update(paths[i]);
      }
    }
  }

  d3.json("<?php echo $trees ?>", function(error,values){
    root = values;
    select2_data = extract_select2_data(values,[],0)[1];//I know, not the prettiest...
    root.x0 = height / 2;
    root.y0 = 0;
    root.children.forEach(collapse);
    update(root);
    //init search box
    $("#search").select2({
      data: select2_data,
      containerCssClass: "search"
    });
  });
  //attach search box listener
  $("#search").on("select2-selecting", function(e) {
    var paths = searchTree(root,e.object.text.split(" |")[0],[]);
    if(typeof(paths) !== "undefined"){
      openPaths(paths);
    }
    else{
      alert(e.object.text+" not found!");
    }
  })

  d3.select(self.frameElement).style("height", "800px");

  function update(source) {
    // Compute the new tree layout.
    var nodes = tree.nodes(root).reverse(),
    links = tree.links(nodes);

    // Normalize for fixed-depth.
    nodes.forEach(function(d) { d.y = d.depth * 180; });

    // Update the nodesâ€¦
    var node = svg.selectAll("g.node")
      .data(nodes, function(d) { return d.id || (d.id = ++i); });

    // Enter any new nodes at the parent's previous position.
    var nodeEnter = node.enter().append("g")
      .attr("class", "node")
    .attr("transform", function(d) { return "translate(" + source.y0 + "," + source.x0 + ")"; })
    .on("click", click);

    nodeEnter.append("circle")
    .attr("r", 1e-6)
    .style("fill", function(d) { return d._children ? "lightsteelblue" : "#fff"; });

    nodeEnter.append("text")
      .attr("x", function(d) { return d.children || d._children ? -10 : 10; })
      .attr("dy", ".35em")
      .attr("text-anchor", function(d) { return d.children || d._children ? "end" : "start"; })
      .text(function(d) { return d.username+' '+d.account_holder; })
      .style("fill-opacity", 1e-6);

    // Transition nodes to their new position.
    var nodeUpdate = node.transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + d.y + "," + d.x + ")"; });

    nodeUpdate.select("circle")
      .attr("r", 4.5)
      .style("fill", function(d) {
        if(d.class === "found"){
          return "#ff4136"; //red
        }
        else if(d._children){
          return "lightsteelblue";
        }
        else{
          return "#fff";
        }
      })
      .style("stroke", function(d) {
        if(d.class === "found"){
          return "#ff4136"; //red
        }
    });

    nodeUpdate.select("text")
      .style("fill-opacity", 1);

    // Transition exiting nodes to the parent's new position.
    var nodeExit = node.exit().transition()
      .duration(duration)
      .attr("transform", function(d) { return "translate(" + source.y + "," + source.x + ")"; })
      .remove();

    nodeExit.select("circle")
      .attr("r", 1e-6);

    nodeExit.select("text")
      .style("fill-opacity", 1e-6);

    // Update the linksâ€¦
    var link = svg.selectAll("path.link")
      .data(links, function(d) { return d.target.id; });

    // Enter any new links at the parent's previous position.
    link.enter().insert("path", "g")
      .attr("class", "link")
      .attr("d", function(d) {
        var o = {x: source.x0, y: source.y0};
        return diagonal({source: o, target: o});
      });

    // Transition links to their new position.
    link.transition()
      .duration(duration)
      .attr("d", diagonal)
      .style("stroke",function(d){
        if(d.target.class==="found"){
          return "#ff4136";
        }
      });

    // Transition exiting nodes to the parent's new position.
    link.exit().transition()
      .duration(duration)
      .attr("d", function(d) {
        var o = {x: source.x, y: source.y};
        return diagonal({source: o, target: o});
      })
      .remove();

    // Stash the old positions for transition.
    nodes.forEach(function(d) {
      d.x0 = d.x;
      d.y0 = d.y;
      });
  }
</script>


<?php echo $footer; ?>