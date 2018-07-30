<html>
    <head>
        <title>AutoPlotter</title>

        <!-- Latest compiled and minified Boostrap CSS and Javascript -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

        <!-- Slider -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/css/bootstrap-slider.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.8.1/bootstrap-slider.min.js"></script>

        <!-- Tab Icon -->
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
 
        <!-- My Code -->
        
        <!-- CSS -->
        <style>
            body {
                padding-top: 40px;
            }

            img:hover {
                border: 1px solid #cecece;
            }
            
            .well-image .bg {
                pointer-events: none;
                margin-bottom: 0px;
                opacity: 0.2;
                color: #fff;
                background: #fff url("") no-repeat center top;
                background-color: #ffffff !important;
                background-size: contain !important;

                -webkit-filter: blur(5px);
                -moz-filter: blur(5px);
                -o-filter: blur(5px);
                -ms-filter: blur(5px);
                filter: blur(5px);

                overflow: hidden;
                width: 115%;
                height: 100%;

                position: absolute;
                top:0;left:0;
                z-index:auto;
            }
            
            .well {
                overflow: hidden;
                width: 115%;
            }
            
            .tooltip-txt {
                white-space: pre;
                max-width : 100px;
                word-wrap: break-word;
            }

        </style>

        <!-- PHP -->
        <?php

            $images = array();

            function newImage($new_png, $new_pdf, $new_txt, $new_width, $new_height) {
                global $images;
                $new_image = array(
                    "png_path" => $new_png,
                    "pdf_path" => $new_pdf,
                    "txt_path" => $new_txt,
                    "width" => $new_width,
                    "height" => $new_height,
                );

                $images[] = $new_image;
            
            }

            $cwd = getcwd();

            $pdf_path = ("pdfs/");
            $png_path = ("pngs/");
            $txt_path = ("txts/");

            $pdfs = scandir($cwd . "/" . $pdf_path);
            $pngs = scandir($cwd . "/" . $png_path);
            $txts = scandir($cwd . "/" . $txt_path);

            
            // Fill JSON
            $txt_offset = 0;
            for ($i = 0; $i < count($pngs); $i++) {
                $png_name = explode(".", $pngs[$i]);
                $pdf_name = explode(".", $pdfs[$i]);
                $txt_name = explode(".", $txts[$i - $txt_offset]);
                if ($png_name[0] != $pdf_name[0]) continue;
                if ($pngs[$i] == '.' || $pngs[$i] == '..') continue;
                list($width, $height) = getimagesize($cwd . "/" . $png_path . "/" . $pngs[$i]);
                if ($png_name[0] != $txt_name[0]) {
                    newImage($png_path . $pngs[$i], $pdf_path . $pdfs[$i], "None", $width, $height);
                    $txt_offset++;
                    continue;
                }
                newImage($png_path . $pngs[$i], $pdf_path . $pdfs[$i], $txt_path . $txts[$i - $txt_offset], $width, $height);
            }

        ?>

        <!-- JQuery -->
        <script type="text/javascript">

            var php_out = <?php echo json_encode($images); ?>;
            var indexMap = {"thumbnails":{"width": 0, "height": 0}, "search":""};
            var page_loads = 0;

            $(function() {
                page_loads++;
                load_page(php_out);
            });

            function load_page(php_out) {
                var data = make_json(php_out);
                filter(data);
                fill_sections(data);
                $('[id^=img_]').mouseenter(
                    function() {
                        // Dynamic Well Preview
                        $("#preview").attr("src", $(this).attr("src"));

                        // Dynamic Well Tooltip
                        new_txt = data[Number($(this).attr('id').split("_")[1])]["txt_path"];
                        if (new_txt != "None"){
                            $("#tooltip").show();
                            $("#tooltip").load(new_txt);
                        }
                        else {
                            $("#tooltip").hide();
                        }
                    } 
                );

                $("#slider").bootstrapSlider({
                        id: "slider0",
                        ticks: [0, 100, 200],
                        ticks_labels: ['0%', '100%', '200%'],
                        ticks_snap_bounds: 5,
                        tooltip_position: 'bottom'
                });
                $("#slider").on("slide", function(slideEvt) {
                    $("img").attr("width", indexMap["thumbnails"]["width"]*(slideEvt.value/100));
                    $("img").attr("height", indexMap["thumbnails"]["height"]*(slideEvt.value/100));
                });

                // Ensure that images are drawn with slideEvt value when page is loaded (so if page is refreshed by search funct, maintain size)
                var val = $("#slider").attr("value");
                $("img").attr("width", indexMap["thumbnails"]["width"]*(val/100));
                $("img").attr("height", indexMap["thumbnails"]["height"]*(val/100));

            }

            function make_json(php_out) {
                var new_json = [];
                
                for (var i = 0; i < php_out.length; i++) {
                    var img_obj = php_out[i];
                    var png_path = img_obj["png_path"];
                    var pdf_path = img_obj["pdf_path"];
                    var txt_path = img_obj["txt_path"];
                    var width = img_obj["width"];
                    var height = img_obj["height"];
                    /* var container_width = $("#section_1").width(); */
                    /* var aspect_ratio = height/width; */
                    /* var img_width = container_width/3; */
                    /* var img_height = img_width*aspect_ratio; */
                    var name = png_path.split('/').reverse()[0].split('.')[0];


                    // Get divisor for image dimensions
                    var div = get_div(Math.max(width, height));
                    indexMap["thumbnails"]["width"] = width/div;
                    indexMap["thumbnails"]["height"] = height/div;

                    new_json.push({
                        "name": name,
                        "png_path": png_path,
                        "pdf_path": pdf_path,
                        "txt_path": txt_path,
                        "width": indexMap["thumbnails"]["width"],
                        "height": indexMap["thumbnails"]["height"],
                        "hidden": false,
                    });
                }                
                
                console.log(new_json);
                return new_json;
            }

            function get_div(max_length) {
                var divisor = 1;
                while (true) {
                    if (max_length/divisor <= 250) {
                        return divisor;
                    }
                    divisor+=0.5;
                }
            }

            function refresh() {
                page_loads++;
                load_page(php_out);
            }

            function filter(data) {

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
                for (var i = 0; i < data.length; i++) {
                    if (data[i]["name"].indexOf(search) < 0) {
                        data[i]["hidden"] = true;
                    }
                }

                return data;
            }

            function set_grid(data) {
                var container = $("#section_1");
                var counter = 0;

                container.html("");
                var toappend = "";

                for (var i = 0; i < data.length; i+=3) {

                    //Draw thumbnails
                    toappend += "<div class='row'>";
                    for (var j = 0; j < 3; j++){
                        if (counter > data.length+1) return;
                        toappend +=     ("<div id=grid_" + (i + j) + " class='col-sm-4 text-center'></div>");
                        counter++;
                    }
                    toappend += "</div>";
                }
                container.append(toappend);
            }

            function fill_grid(data) {

                $("#content").html("");
                $("#content").append("<row>");

                var counter = 0;
                var new_search = "";

                var true_count = 0;
                var grid = [];
                for (var i = 0; i < data.length; i++) {
                    console.log(data[i]["name"]);
                    if (data[i]["hidden"]) {
                        true_count++;
                        continue;
                    }

                    var new_split = "";
                    var html_name = "";

                    if (indexMap["search"] != "") {
                        new_search = indexMap["search"];
                        new_split = data[i]["name"].split(new_search);
                        new_name = "";

                        for (var j = 0; j < new_split.length; j++) {
                            new_name += new_split[j];
                            html_name += new_split[j];
                            if (data[i]["name"].indexOf(new_name + new_search) != -1) {
                                new_name += new_search;
                                html_name += "<font class='bg-success'>"+new_search+"</font>";
                            }
                        }
                    }

                    else {
                        html_name = data[i]["name"];
                    }


                    grid.push("<div style='display:inline-block'><a href="+data[i]["pdf_path"]+"><h4>"+html_name+"</h4><img id=img_"+true_count+" src="+data[i]["png_path"]+" width="+data[i]["width"]+" height="+data[i]["height"]+"></a></div>");
                    true_count++;
                    counter++;
                }
                $("#content").append(...grid);
                $("#content").append("</row>");
            }

            function fill_sections(data) {
                //set_grid(data);
                fill_grid(data);
            }

        </script>
    </head>

    <body>

        <div class="container-fluidi">
            <div class="row">
                <!-- Tooltip -->
                <div class="col-md-3">
                    <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="well">
                            <h4>AutoPlotter</h4>
                            <div class="text-center">
                                <form>
                                    <div class="form-group">
                                        <input type="text" id="search" onkeyup="refresh()" placeholder="Search" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <input id="slider" type="text" data-slider-ticks="[0, 100, 200]" data-slider-ticks-snap-bounds="5" data-slider-ticks-labels='["0%", "100%", "200%"]' data-slider-value="100"/>
                                    </div>
                                </form>
                                <a style="z-index: auto; position: relative;" class="btn btn-primary btn-sm" href=<?php echo $_SERVER['HTTP_REFERER'] ?> role="button">&laquo; Back</a>
                            </div>
                        </div>

                        <div class="well">
                            <div class="text-center">
                                <img id="preview" class="img-thumbnail" src="" alt="Hover over a thumbnail to get preview here" width=200 height=300>
                            </div>
                        </div>

                        <div class="well">
                            <div id="tooltip" class="tooltip-txt"></div>
                        </div>
                    </div>
                    </div>

                </div>
                <!-- Images -->
                <div class="col-md-9">

                    <!-- Images -->
                    <div class="row">
                        <!-- <div id="section_1" class="container">
                        </div> -->
                        <div id="content" class="d-flex flex-wrap">
                        </div><!-- /.container -->
                    </div>

                    <!-- Footer -->
                    <div class="row">
                        <hr> <!-- thin, grey horizontal line -->
                        <footer>
                            <p>Made by Jonathan Guiang 2017</p>
                        </footer>
                    </div>

                </div>
            </div>
        </div>        

    </body>
</html>
