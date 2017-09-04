# AutoPlotter
There are a lot of other plotters out there, but our reviews speak for themselves: AutoPlotter is the new craze! Clone this repository to start using AutoPlotter today! **_AutoPlotter: Simple, stunning, smart._**

Example: http://uaf-8.t2.ucsd.edu/~jguiang/AutoPlotter

## Features:
1. Beautiful Bootstrap-based UI
2. Quick search function with search highlights
3. Easily share search outputs with unique search URL's
4. Zoom slider for resizing thumbnails
    * If your page is too crowded, just set the slider to 0 to get a handy, still searchable list of your files
5. Dynamic preview display for getting quick accces to vital histogram information
    * Include a `.txt` file in the `/txts` directory to link it to a histogram with the same name (i.e. `foo.txt` will be displayed when the user mouses over `foo.png`)

## Reviews:
###### Jonathan Guiang
> It's way better than Niceplots.

###### New York Times
> AutoPlotter is so easy to use, every experimental physicist should be using this!

## Using AutoPlotter:
#### Setting up an AutoPlotter page
1. Clone this repository
2. Ensure you are on a system that supports public html dumps
3. Run the bash script:
```
    # Make sure to pass both the 'setup' command as well as the name of the 
    # directory you wish to use (AutoPlotter creates the directory for you)

    ./run.sh setup new_dirname
```

4. Run the bash script again:
```
    # This time, pass the 'updt' command, the name of the directory you wish 
    # to update, and the name of the directory you are pulling files from

    ./run.sh updt new_dirname ~/home/users/user_name/target_dirname
```
