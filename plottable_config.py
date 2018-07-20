import os

# Favicon
raw_favicon_ico = "https://raw.githubusercontent.com/jkguiang/AutoPlotter/master/favicon.ico"
# AutoPlotter base files 
raw_main_php = "https://raw.githubusercontent.com/jkguiang/AutoPlotter/master/main.php"
raw_main_js = "https://raw.githubusercontent.com/jkguiang/AutoPlotter/master/main.js" 
url_cms_jpg = "https://home.cern/sites/home.web.cern.ch/files/image/update-for_the_public/2015/01/cms.jpg"
staging_area = "" # For storing current 'staging area' (new autoplotter dir)
aplot_base_map = {raw_main_php:"index.php", raw_main_js:"main.js", raw_favicon_ico:"favicon.ico", url_cms_jpg:"cms.jpg"}

# AutoPlotter branch files
raw_index_php = "https://raw.githubusercontent.com/jkguiang/AutoPlotter/master/index.php"
aplot_branch_map = {raw_index_php:"index.php", raw_favicon_ico:"favicon.ico"}

# Paths
public_html = "{0}/public_html".format(os.getenv("HOME"))
