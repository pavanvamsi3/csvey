$(document).ready(function(){

    $("#search").click(function(){

        clearCanvas();

        var searchterm = $("#term").val() ? $("#term").val() : "github";

        function getUserData(callback) {
            $.get("http://api.github.com/users/" + searchterm,
                function(data, status){
                    console.log(status);
                    success: callback(data, status);
            });
        };

        function getUserRepos(callback){
            //$.get("https://api.github.com/users/" + searchterm + "/repos",
            $.get("https://www-marker.practodev.com/surveyquestions/"+ searchterm,
                function(data, status){
                    console.log(status);
                    success: callback(data,status);
            });
        };

        function getRepoLanguages(callback,repo){
            //$.get("https://api.github.com/repos/" + searchterm + "/" + repo + "/languages",
            $.get("https://www-marker.practodev.com/surveyoptions/" + repo,
                function(data, status){
                        console.log(status);
                        success: callback(data,status,repo);
            });
        };

        function showUser(data, status){
            console.log(status);
            var username = "<h3>" + data.login + "</h3>";
            $("#username").append(username);
        };

        function showSurveys(data, status){
            data = JSON.parse(data)
            console.log(status);
            for (var i = 0; i < data.length; i++) {
                $("#repoDetails").append("<li id=" + data[i].id + ">" + data[i].question + "</li>");
            };

            // function when user clicks a repo choice
            $("#repoDetails").children().click(function(){

                // Clear previous details
                // $("#langDetails").children().remove();

                // Get repo id
                var repoChoice = (this.id)
                getRepoLanguages(showLangs, repoChoice);

            });
        };

        function showLangs(data, status,repo) {
            data = JSON.parse(data);
            var dataset = [];

            // loop through data object and append items to li
            for (var key in data) {
                if (data.hasOwnProperty(key)) { // ensure it is key from data, not prototype being use
                    var item = new Object();
                    item.key = data[key].value;
                    item.value = data[key].count;
                    dataset.push(item);
                };
            }

        // D3 Code from now onwards

        // Scales
            xScale.domain(dataset.map(function (d) {return d.key; }))
              .rangeRoundBands([margin.left, w], 0.05);

            yScale.domain([0, d3.max(dataset, function(d) {return d.value; })])
              .range([h,margin.top]);

        // Axis
            xAxis.scale(xScale).orient("bottom");

            yAxis.scale(yScale).orient("left");

        // Create bars and labels
          bars = svg.selectAll("rect").data(dataset);
          barLabels = svg.selectAll("text").data(dataset);

        // Add new bars
          bars.enter()
          .append("rect")
          .attr("x", function(d, i) {
              return xScale(d.key);
            })
            .attr("y", function(d) {
              return yScale(d.value);
            })
            .attr("width", xScale.rangeBand())
            .attr("height", function(d) {
              return h - yScale(d.value);
            })
            .attr("fill", "steelblue");

        // Remove bars as necessary
            bars.exit()
          .transition()
          .duration(500)
          .attr("x", w)
          .remove();

        // Update the bars
            bars.transition()
            .duration(750)
            .attr("x", function(d,i) {
              return xScale(d.key);
            })
            .attr("y", function(d) {
              return yScale(d.value);
            })
            .attr("width", xScale.rangeBand())
            .attr("height", function(d) {
              return h - yScale(d.value);
            });

        // Update the x axis
            svg.select(".xaxis")
                .transition()
                .duration(750)
            .call(xAxis);

        // Update the y axis
            svg.select(".yaxis")
                .transition()
                .duration(750)
                .call(yAxis);

        // Update the title
            // svg.select(".chartTitle")
            //     .text(repo);

        // Add tooltip
            bars.on("mouseover",function(d){

                // add blank tooltip
                svg.append("text")
                    .attr("id","tooltip");

                // get the x and y coords
                var xPosition = parseFloat(d3.select(this).attr("x")) + xScale.rangeBand()/2;
                var yPosition = parseFloat(d3.select(this).attr("y")) + 18;

                // add the tooltip
                svg.select("#tooltip")
                    .attr("x",xPosition)
                    .attr("y",function(){
                        // if value is less than 10% of max, show tooltip above bar
                        var mx = d3.max(dataset, function(d) {return d.value; });
                        if (d.value < 0.1 * mx) {
                            return yPosition - 22;
                        } else {
                            return yPosition;
                        };
                    })
                    .attr("text-anchor","middle")
                    .attr("fill",function(){
                        // if value is less than 10% of max, make tooltip black
                        var mx = d3.max(dataset, function(d) {return d.value; });
                        if (d.value < 0.1 * mx) {
                            return "black";
                        } else {
                            return "white";
                        };
                    })
                    .attr("font-family","sans-serif")
                    .attr("font-size","12px")
                    .text(d.value);

            })
            .on("mouseout",function(){
                d3.select("#tooltip").remove();
            });

// Pie Chart from here
            var metadata = {
                'x' : 'name' ,
                'y' : 'percent'
            };
            console.log(data);
            var dataset = [];
            var sum = 0;
            for (var key in data) {
                if (data.hasOwnProperty(key)) { // ensure it is key from data, not prototype being use
                    var item = new Object();
                    item.key = data[key].value;
                    item.value = data[key].count;
                    sum = sum + parseInt(data[key].count, 10);
                    dataset.push(item);
                };
            }

            var width = 600 , height = 650 , radius = 150 ,
            color = ["#C5AAF5","#FB7374","#A3CBF1","#79BFA1","#F5A352","#94B3C8", "#F9D08B","#B2AC4E","#64BD4F","#C09372"];

            var colorDescriptions = [];
            d3.select("#xyz").remove();
            var svgContainer = d3.select("#pie") // create svg container
                .append("svg:svg")
                .data([dataset])
                .attr("width", width)
                .attr("height", height)
                .attr('id', 'xyz')
                .append("svg:g")
                .attr("transform", "translate(" + 250 + "," + 250 + ")") ;

            var arc = d3.svg.arc() // draw arc of given radius
                .outerRadius(radius);

            var pie = d3.layout.pie() // assign data for pie chart
                .value(function(d) { return d.value; });

            var arcs = svgContainer.selectAll("g.slice") // slice the circle
                .data(pie)   
                .enter()
                .append("svg:g")
                .attr("class", "slice");

            arcs.append("svg:path") // fill color in each slice
                .attr("fill", function(d, i) { 
                var colorSelected =  color[i];
                colorDescriptions.push({"colorSelected": colorSelected, "label": dataset[i].key});
                return colorSelected; } )
                .attr("d", arc)
            arcs.append("svg:text") // write slice information values
                .attr("transform", function(d) {
                d.innerRadius = 0;
                d.outerRadius = radius;
                    return "translate(" + arc.centroid(d) + ")";
                })
                .attr("text-anchor", "middle")
                .text(function(d, i) { return (Math.round(dataset[i].value*100/sum)) + '%'; })
                .style("font-family","monospace")
                .style("fill", "#3f3f3f")
                .style("font-size", "20px");

            descriptionText = "Pie Chart"; // pie chart description

            var description = svgContainer.append("g").attr("class", "description"); // pie chart description
            var desc_label = description.append("text")
                .attr("class", "description")
                .attr("y", -200)
                .attr("x", 000)
                .text(descriptionText)
                .style("font-weight", "bold")
                .style("font-size", "17px")
                .style("text-anchor", "middle"); 

            var pieChartLabels = svgContainer.append("g").attr("id","pie-chart-labels");   //index for pie chart : name
            pieChartLabels.selectAll("text").data(colorDescriptions).enter().append("svg:text")
                .text(function(d) { return d.label; } ).attr("x",240)
                .attr("y",function(d, i) { return 14 + i*30; })
                .style("font-size", "15px");

            var pieChartLabelsColors = svgContainer.append("g").attr("id","pie-chart-labels-colors"); 
            pieChartLabelsColors.selectAll("rect").data(colorDescriptions).enter().append("rect") 
                .attr("x",200)
                .attr("y",function(d, i) { return i*30; })
                .attr("width", 25)
                .attr("height", 15)
                .style("fill" , function(d) { return d.colorSelected; }); //index for pie chart : color

        }; // end of the showLangs function


        // call the user and repos functions
        //getUserData(showUser);
        getUserRepos(showSurveys);


        // setup for the d3 chart
        // basic SVG setup
        var dataset = [];
        var margin = {top: 70, right: 20, bottom: 60, left: 100};
        var w = 600 - margin.left - margin.right;
        var h = 500 - margin.top - margin.bottom;

        //Create SVG element
        var svg = d3.select("div#chart")
        .append("svg")
        .attr("width", w + margin.left + margin.right)
        .attr("height", h + margin.top + margin.bottom);

      // define the x scale
        var xScale = d3.scale.ordinal()
        .domain(dataset.map(function (d) {return d.key; }))
        .rangeRoundBands([margin.left, w], 0.05);

        // define the x axis
        var xAxis = d3.svg.axis().scale(xScale).orient("bottom");

        // define the y scale
        var yScale = d3.scale.linear()
        .domain([0, d3.max(dataset, function(d) {return d.value; })])
        .range([h,margin.top]);

        // define the y axis
        var yAxis = d3.svg.axis().scale(yScale).orient("left");

        // draw the x axis
        svg.append("g")
        .attr("class", "xaxis")
        .attr("transform", "translate(0," + h + ")")
        .call(xAxis);

        // draw the y axis
        svg.append("g")
        .attr("class", "yaxis")
        .attr("transform","translate(" + margin.left + ",0)")
        .call(yAxis);

        // add the x axis label
        svg.append("text")
            .attr("class", "x axis label")
            .attr("text-anchor", "middle")
            .attr("transform", "translate(" + (w / 2) + "," + (h + (margin.bottom / 2) + 10) + ")")
            .text("Options");

        // add the y axis label
        svg.append("text")
            .attr("class", "y axis label")
            .attr("text-anchor", "middle")
            .attr("transform", "translate(15," + (h / 2) + ")rotate(-90)")
            .text("No. of users");


        // add a title to the chart
        svg.append("text")
            .attr("class", "chartTitle")
            .attr("text-anchor", "middle")
            .style("font-weight", "bold")
            .style("font-size", "17px")
            .style("text-anchor", "middle")
            .attr("transform", "translate(" + (w / 2) + ",20)")
            .text("Graph");

    }); // end of search click function

    // respond to click on clear button
    $("#clear").click(function(){
        $("#term").val(''); // extra detail to clear out input box
        clearCanvas();
    });

    // clear the elements out
    var clearCanvas = function(){
        $("li").remove(); // clear out list items
        $("h3").remove(); // clear out username heading
        $("h4").remove(); // clear out heading "Repos"
        $("#searchRepo").remove(); // clear out button
        d3.selectAll("svg").remove(); // clear out chart

    };
    var clearCanvas2 = function() {
        d3.selectAll("svg").remove();
    }
});
