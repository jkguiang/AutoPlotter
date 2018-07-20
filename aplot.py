import os
import plottable_config as pc

def setup(f_url, dest, f_name):
    if not os.path.isdir(dest):
        os.mkdir(dest)
    os.system("curl {0} > {1}/{2}".format(f_url, dest, f_name))

def autoplotter(targ_dir, typ):
    if targ_dir[-1] == "/": 
        targ_dir = targ_dir[:-1]
    targ_name = targ_dir.split("/")[-1]
    if typ == "base":
        print("Building base...")
        pc.staging_area = "{0}/{1}".format(pc.public_html, targ_name)
        for f_url in pc.aplot_base_map:
            setup(f_url, pc.staging_area, pc.aplot_base_map[f_url])
    if typ == "branch":
        print("Building branch...")
        for f_url in pc.aplot_branch_map:
            os.system("cp -r {0} {1}/{2}".format(targ_dir, pc.staging_area, targ_name))
            setup(f_url, "{0}/{1}".format(pc.staging_area, targ_name), pc.aplot_branch_map[f_url])
    os.system("chmod -R 755 {0}".format(pc.staging_area))
