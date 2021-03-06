indexMap = {"search":""}; // 'Maps' list from database to objects in javascript
page_loads = 0; // Tracks how many times the page has been loaded

// Search function
function filter() {

    var input = document.getElementById('search');
    if (page_loads == 1 && window.location.hash != "") {
        var search = window.location.hash.split("#")[1];
    }
    else{
        if (input == "") {
            window.location.hash = "";
        }
        var search = input.value;
        window.location.hash = search;
    }
    indexMap["search"] = search;

    return;
}

// Highlights letters that have been searched
function highlight_search(name) {
    var new_search = indexMap["search"];
    var new_split = name.split(new_search);
    var new_name = "";
    var html_name = "";

    for (var l = 0; l < new_split.length; l++) {
        new_name += new_split[l];
        html_name += new_split[l];
        if (name.indexOf(new_name + new_search) != -1) {
            new_name += new_search;
            html_name += "<font class='bg-success'>"+new_search+"</font>";
        }
    }

    return html_name;
}

function load_page(php_out) {

    var data = make_json(php_out);

    page_loads += 1;
    filter();

    var cur_url = window.location.href.split("#")[0];

    var to_append = ""; // to store string of html to append to ul
    var div = $("#main_list"); // points to the 'div' (division) html element
    div.html(""); // clear all html contained by <div></div>
    search = indexMap["search"];
    var results_exist = false;
    $.each(data, function(key, val) {
        if (val["name"].indexOf(search) >= 0) {
            results_exist = true;
            var html_name = highlight_search(val["name"]);

            to_append += "<div class='container' id='"+val["name"]+"'><hr><div class='col-sm-4'><div class='text-center'><p><h4><a href='"+cur_url+val["name"]+"'>"+html_name+"</a></h4></p>"
                        +"<a href='"+cur_url+val["name"]+"'><img id='"+val["img"].split(".")[0]+"' src='"+val["img"]+"' width='"+val["img_width"]+"' height='"+val["img_height"]+"'></a></div></div>"
                        +"<div class='col-sm-8'><p><h4>Properties</h4>Contains: "+val["count"]+" plot(s)<br />Last modified: "+val["modified"]+"</p></div></div>";
        }
    });
    if (results_exist == false) {
        if (data.length == 0) {
            div.append("<div class='container text-center'><p>No entries exist.</p></div>")
        }
        else {
            div.append("<div class='container text-center'><p>No entries matching '"+search+"' exist.</p></div>")
        }
    }
    div.append(to_append);

}

function make_json(php_out) {
    var new_json = [];
    
    for (var i = 0; i < php_out.length; i++) {
        var dir_obj = php_out[i];
        var name = dir_obj["name"];
        var count = dir_obj["count"];
        var img = dir_obj["img"];
        var img_width = dir_obj["img_width"];
        var img_height = dir_obj["img_height"];
        var modified = new Date(Number(dir_obj["modified"]*1000));

        new_json.push({
            "name": name,
            "count": count,
            "img": img,
            "img_width": 0.5*Number(img_width),
            "img_height": 0.5*Number(img_height),
            "modified": modified,
        });

    }                
    
    console.log(new_json);
    return new_json;
}

// Refreshes page, need to call here because can't pass php_out arg from html
function refresh() {
    load_page(php_out);
}

// Main function
$(function() {
    console.log(localStorage);

    load_page(php_out);

    // Initital hides
    $("#load").hide();
    $("#load_msg").hide();
    $("#finished").hide();
    $("#input_err").hide();
    $("#internal_err").hide();
    $("#SingleMuon").hide();
    $("#Cosmics").hide();

    // Initial Disables
    $("#submit").attr('disabled', 'disabled');

    // Prevent 'enter' key from submitting forms (gives 404 error with full data set name form)
    $(window).keydown(function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
            return false;
        }
    });

});
