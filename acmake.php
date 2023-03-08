<?php

// get the file extention from user input
if (isset($argv[1])) {
  $extension = strtolower($argv[1]);
} else {
  do {
    echo "Please provide a file extension (c/cpp): ";
    $extension = strtolower(trim(fgets(STDIN)));
  } while (!in_array($extension, ['c', 'cpp']));
}

if (isset($argv[2])) {
  $project_name = $argv[2];
} else {
  do {
    echo "Please provide a project name: ";
    $project_name = trim(fgets(STDIN));
  } while (empty($project_name));
}

if (!empty($extension) && !empty($project_name)) {
  if (file_exists($project_name)) {
    do {
      echo "Warning: A project with a similar name already exists in the current directory.\n";
      echo "Please provide another project name: ";
      $project_name = trim(fgets(STDIN));
    } while (file_exists($project_name));

    mkdir($project_name);
    mkdir($project_name . "/src");
    mkdir($project_name . "/build");
    mkdir($project_name . "/include");
  } else {
    mkdir($project_name);
    mkdir($project_name . "/src");
    mkdir($project_name . "/build");
    mkdir($project_name . "/include");
  }
}

$file_name = "main." . $extension;

if ($file_name == "main.c") {
  $c_program = <<<'PROGRAM'
    #include <stdio.h>
    #include <string.h>
    
    struct ProgrammingLanguage {
        const char* name;
        const char* author;
    };
    
    void greet(const struct ProgrammingLanguage* pl) {
        printf("Happy programming with %s, created by %s!\n", pl->name, pl->author);
    }
    
    int main(void) {
        struct ProgrammingLanguage c = {"C", "Dennis Ritchie"};
        greet(&c);
        return 0;
    }
    PROGRAM;
  file_put_contents($project_name . "/src/main.c", $c_program);
} else if ($file_name == "main.cpp") {
  $cpp_program = <<<'PROGRAM'
    #include <iostream>
    #include <string>

    template <typename T>
    struct ProgrammingLanguage {
        std::string name;
        std::string author;
    };

    template <typename T>
    void greet(const ProgrammingLanguage<T>& pl) {
        std::cout << "Happy programming with " << pl.name << ", created by "
                << pl.author << "!\n";
    }

    int main() {
        ProgrammingLanguage<std::string> cpp {"C++", "Bjarne Stroustrup"};
        greet(cpp);
        return 0;
    }
    PROGRAM;
  file_put_contents($project_name . "/src/main.cpp", $cpp_program);
}



// check if cmake is installed
$cmake_installed = shell_exec('command -v cmake');

if (!$cmake_installed) {
  echo "CMake is not installed. Downloading...\n";

  // download CMake
  $download_command = "sudo apt-get install cmake -y";
  shell_exec($download_command);

  // get CMake version
  $cmake_version = trim(shell_exec('cmake --version | head -n 1 | cut -d" " -f3'));
} else {
  // get CMake version
  $cmake_version = trim(shell_exec('cmake --version | head -n 1 | cut -d" " -f3'));
}

if ($extension === 'c') {
  $cmake_list = <<<PROGRAM
  cmake_minimum_required(VERSION $cmake_version)
  
  project(
    $project_name
    LANGUAGES C
    VERSION 0.0.1)
  
  # Set C standard
  set(C_STANDARD 17)
  set(C_STANDARD_REQUIRED ON)
  set(CMAKE_C_FLAGS "\${CMAKE_C_FLAGS} -std=c11")

  # Set the include directory
  include_directories(\${PROJECT_SOURCE_DIR}/include) 

  # Create the executable
  add_executable($project_name)
  
  # Add the source files
  file(GLOB_RECURSE SRC_FILES 
      src/*.h
      src/*.c)
  target_sources($project_name PRIVATE \${SRC_FILES})
  
  # Add compiler warnings
  target_compile_options(
    $project_name
    PRIVATE -Werror
            -Wall
            -Wextra
            -Wpedantic
            -Wformat=2 
            -Wno-unused-parameter 
            -Wshadow
            -Wwrite-strings 
            -Wstrict-prototypes 
            -Wold-style-definition
            -Wredundant-decls 
            -Wnested-externs 
            -Wmissing-include-dirs
            -Wjump-misses-init 
            -Wlogical-op
  )
  PROGRAM;
} else {
  $cmake_list = <<<PROGRAM
  cmake_minimum_required(VERSION $cmake_version)
  
  project(
    $project_name
    LANGUAGES CXX
    VERSION 0.0.1)
  
  # Set C++ standard
  set(CXX_STANDARD 20)
  set(CXX_STANDARD_REQUIRED ON)
  set(CMAKE_CXX_FLAGS "\${CMAKE_CXX_FLAGS} -std=c++20")

  # Set the include directory
  include_directories(\${PROJECT_SOURCE_DIR}/include) 

  # Create the executable
  add_executable($project_name)
  
  # Add the source files
  file(GLOB_RECURSE SRC_FILES 
      src/*.h
      src/*.cpp 
      src/*.cc
      src/*.cxx)
  target_sources($project_name PRIVATE \${SRC_FILES})
  
  # Add compiler warnings
  target_compile_options(
    $project_name
    PRIVATE -Werror
            -pedantic-errors
            -Wall
            -Wextra
            -Wconversion
            -Wsign-conversion
            -Wshadow
            -Wnon-virtual-dtor
            -Wpedantic
            -Wold-style-cast
            -Wcast-align
            -Wunused
            -Woverloaded-virtual
            -Wmisleading-indentation
            -Wduplicated-cond
            -Wduplicated-branches
            -Wlogical-op
            -Wnull-dereference
            -Wuseless-cast
            -Wdouble-promotion
            -Wformat=2
            -Wimplicit-fallthrough
            -Weffc++
  )
  PROGRAM;
}
file_put_contents($project_name . "/CMakeLists.txt", $cmake_list);

if (file_exists($project_name . '/CMakeLists.txt')) {
  chdir($project_name . '/build');
  $output1 = shell_exec('cmake -DCMAKE_EXPORT_COMPILE_COMMANDS=ON ..');
  $output2 = shell_exec('make');
  passthru('./' . $project_name);
}
