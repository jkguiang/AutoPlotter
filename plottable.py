import os
import sys

import plottable_config as pc
import aplot

def sort_files(targ_dir):
    targ_files = os.listdir(targ_dir)
    sorted_files = {"pdf":[], "png":[], "txt":[]}
    for tf in targ_files:
        if os.path.isfile("{0}/{1}".format(targ_dir, tf)):
            try:
                sorted_files[tf.split(".")[-1]].append(tf)
            except KeyError:
                continue

    return sorted_files
            
def handle_dir(targ_dir):
    abs_targ = os.path.abspath(targ_dir)
    targ_files = sort_files(abs_targ)
    req_dirs = ["pdfs", "pngs", "txts"]
    for r_dir in req_dirs:
        cur_path = "{0}/{1}".format(abs_targ, r_dir)
        if not os.path.isdir(cur_path):
            os.mkdir(cur_path)
        for f in targ_files[r_dir.split("s")[0]]:
            os.rename("{0}/{1}".format(abs_targ, f), "{0}/{1}/{2}".format(abs_targ, r_dir, f))
    print("Done")

def handler(arg, plot):
    abs_arg = os.path.abspath(arg)
    print("Handler called")
    if os.path.isdir(abs_arg):
        contents = os.listdir(abs_arg)
        if "pngs" in contents and "pngs" in contents and "txts" in contents:
            print("{0} is already a plottable directory.".format(abs_arg))
            if plot == True:
                print("New directive: plot branch")
                aplot.autoplotter(abs_arg, "branch")
            return True
        success = False
        for item in contents:
            item_path = "{0}/{1}".format(abs_arg, item)
            if os.path.isdir(item_path):
                print("Found new dir: {0}.".format(item_path))
                success = handler(item_path, plot)
                if not success:
                    return False

        if not success:
            print("Formatting {0}".format(abs_arg))
            handle_dir(abs_arg)
            if plot == True:
                print("New directive: plot branch")
                aplot.autoplotter(abs_arg, "branch")
            return True
        else:
            return True


        return True
    else:
        print("{0} is not a directory.".format(abs_arg))
        return False

def api(args):
    commands = ("-plot")
    if len(args) <= 2:
        try:
            return handler(args[1], False)
        except IndexError:
            print("Usage: python plottable.py <target>")
            return False
    elif args[1] in commands:
        if not os.path.isdir(os.path.abspath(args[2])): return False
        aplot.autoplotter(args[2], "base")
        return handler(args[2], True)
    else:
        return False

if __name__ == "__main__":
    
    success = api(sys.argv)
    # success = handler(sys.argv[1])
    if success:
        print("Success!")
    else: 
        print("Failed.")
