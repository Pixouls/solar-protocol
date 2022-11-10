# Open API V2 - Part 2

## What can you do with this API???

Now that you know what kinds of data you can retrieve with API and how to interact with it, lets look at some example projects.

These examples use P5.JS, mostly because its a convienant and accessible way to start making visual things with a common programming language (Javascript).

You can learn more about P5.JS at https://p5js.org/.

### Set up

In order to run the examples, you will need to run a local server on your machine and one of the simplest ways to do this is with Python. Python is generally pre-installed on Macs, but Windows users will need to download it. You will need to use command line/ terminal.

   * To check if you already have Python installed and the version, open terminal and type `python --version`. If its not installed, install python.
   * Navigate to the directory that contains your HTML file.
   		* Different OS and command line interfaces use slightly different syntax. It is easy to find an introduction to command line for your specific OS by searching online. To run the serrver, all you need to know is how to move around the directory structure. The command to change directory used by most, if not all, systems is `cd` + `name of the directory` or `..` to move up in the directory stucture. It may also be useful to list the contents of the directory. `dir` works in windows. `ls` will lists the contents for Mac.
    * Run the below code based on which version of python you are using.
        * Python 3 `python -m http.server`
        * Python 2 `python -m SimpleHTTPServer`
    * If the server is running properly you should see something like this in the terminal Serving HTTP on 0.0.0.0 port 8000 (http://0.0.0.0:8000/) ...
    * In a browser, go to http://localhost:8000/

## Example Project 1: Data Viz

First, lets make some calls to the API and draw the data.

This project visualizes Solar Protocol data.



## Example Project 2: Image Manipulation

This project uses the Solar Protocol network activity to manipulate an image.