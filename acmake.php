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

if (!empty($extention)) {
    // creates two folder
    mkdir('src');
    mkdir('build');
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
    file_put_contents('src/main.c', $c_program);
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
    file_put_contents('src/main.cpp', $cpp_program);
}
