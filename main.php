<html>
    <head>
        <title>AutoPlotter</title>
        <!-- Latest version of JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <!-- Bootstrap JS and CSS dependencies -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <!-- Tab Icon -->
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />

        <!-- CSS -->
        <style>
        .jumbotron-billboard .cms {
            margin-bottom: 0px;
            opacity: 0.2;
            color: #fff;
            background: #000 url("cms.jpg") center center;
            width: 100%;
            height: 100%;
            background-size: cover;
            overflow: hidden;


            position:absolute;
            top:0;left:0;
            z-index:1;
        }
        .jumbotron {position:relative;padding:50px;}
        .jumbotron .container {z-index:2;
            position:relative;
            z-index:2;
        }
        html {
            position: relative;
            min-height: 100%;
        }
        </style>

        <!-- PHP -->
        <?php
            $dirs = array();

            function newDir($dir_name, $file_count, $img_path, $width, $height) {
                global $dirs;
                $new_dir = array(
                    "name" => $dir_name,
                    "count" => $file_count,
                    "img" => $img_path,
                    "img_width" => $width,
                    "img_height" => $height,
                );

                $dirs[] = $new_dir;
            }

            $cwd = getcwd();
            $dir_list = scandir($cwd);

            // Fill JSON
            for ($i = 0; $i < count($dir_list); $i++) {
                $dir_name = $dir_list[$i];
                if ($dir_name != '.' && $dir_name !='..') {
                    if (count(explode(".", $dir_name)) == 1) { 
                        $png_path = ($dir_name . "/pngs");

                        $file_count = count(scandir($cwd . "/" . $dir_name . "/pdfs"));
                        $imgs = scandir($png_path);
                        $img_path = ($png_path . "/" . $imgs[2]);
                        list($width, $height) = getimagesize($img_path);
                        newDir($dir_name, $file_count, $img_path, $width, $height);
                    }
                }
            }
        ?>

        <!-- pass php values to js vars -->
        <script type="text/javascript">
            var php_out = <?php echo json_encode($dirs); ?>;
        </script>
        <!-- My code -->
        <script src="main.js"></script>

    </head>

    <body>
        <div class="jumbotron jumbotron-billboard">
            <div class="cms"></div>
            <p><br /></p>
            <div class="container">
                <h1>AutoPlotter</h1>
                <p>Simple, stunning, smart.</p>
                <form><input type="text" id="search" onkeyup="refresh()" placeholder="Enter directory name..." class="form-control"></form>
            </div>
        </div>
        <div class="container" id="main_list"></div>
    </body>

</html>
