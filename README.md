# AutoPlotter

[![CodeFactor](https://www.codefactor.io/repository/github/jkguiang/autoplotter/badge/master)](https://www.codefactor.io/repository/github/jkguiang/autoplotter/overview/master)

There are a lot of other plotters out there, but our reviews speak for themselves: AutoPlotter is the new craze! Clone this repository to start using AutoPlotter today! **_AutoPlotter: Simple, stunning, smart._**

#### Content:
1. [Features](https://github.com/jkguiang/AutoPlotter#features)
2. [Reviews](https://github.com/jkguiang/AutoPlotter#reviews)
3. [Using AutoPlotter](https://github.com/jkguiang/AutoPlotter#using-autoplotter)

## Features:
1. Beautiful Bootstrap-based UI
2. Quick search function with search highlights
3. Easily share search outputs with unique search URL's
4. Zoom slider for resizing thumbnails
    * If your page is too crowded, just set the slider to 0 to get a handy, still searchable list of your files
5. Dynamic preview display for getting quick accces to vital histogram information
    * Include a `.txt` file in the same original directory as your .pdfs to link it to a histogram with the same name (i.e. `foo.txt` will be displayed when the user mouses over `foo.png`)

## Reviews:
###### Jonathan Guiang
> It's way better than Niceplots.

###### New York Times
> AutoPlotter is so easy to use, every experimental physicist should be using this!

## Using AutoPlotter:
#### Setting up an AutoPlotter page
1. Clone this repository
2. Ensure you are on one of UCSD's Tier-2 servers. 
3. Run `aplot`, passing the directory with the files (.pdf's with optional, associated .txt files) that you wish to plot:
```
    >> aplot <origin_directory> <optional_target>
```
It's as easy as that! AutoPlotter takes care of the rest for you and, when it's finished, it will provide the link to your new plots.

Example: 
http://uaf-8.t2.ucsd.edu/~jguiang/autoplotter/
