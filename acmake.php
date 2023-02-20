<?php

// get the file extention from user input
if (isset($argv[1])) {
    $extention = strtolower($argv[1]);
} else {
    do {
        echo "Please provide a file extension (c/cpp): ";
        $extention = strtolower(trim(fgets(STDIN)));
    } while (!in_array($extention, ['c', 'cpp']));
}

if (isset($argv[2])) {
    $project_name = $argv[2];
} else {
    do {
        echo "Please provide a project name: ";
        $project_name = trim(fgets(STDIN));
    } while (empty($project_name));
}

if (!empty($extention) && !empty($project_name)) {
    if (file_exists($project_name)) {
        do {
            echo "Warning: A project with a similar name already exists in the current directory.\n";
            echo "Please provide another project name: ";
            $project_name = trim(fgets(STDIN));
        } while (file_exists($project_name));

        mkdir($project_name);
        mkdir($project_name . "/src");
        mkdir($project_name . "/build");
    } else {
        mkdir($project_name);
        mkdir($project_name . "/src");
        mkdir($project_name . "/build");
    }
}

$file_name = "main." . $extention;

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
    
    int main() {
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
