# AutoCMake

AutoCMake is a PHP script that automatically generates CMake-based minimal projects for C and C++. This script is designed for Linux systems, specifically Ubuntu, and requires the `PHP Command Line Interface` to be installed on your machine.

## How to use

To use AutoCMake, follow these steps:

* Clone this repository to your local machine.

* Copy the `acmake.php` file to the directory where you want to create your `C` or `C++` project.

* To create a CMake-based project for `C`, navigate to the directory where the `acmake.php` file is located, and run the script by typing the following command in your terminal:
  ```
  php acmake.php c project_name
  ```
* To create a CMake-based project for `C++`, navigate to the directory where the `acmake.php` file is located, and run the script by typing the following command in your terminal:
  ```
  php acmake.php cpp project_name
  ```
This command will create a project folder that includes a `CMakeLists.txt` file for `C` or `C++` (depending on your command), as well as three folders named `src`, `include`, and `build`. 
