#!/bin/bash
g++ -x c++ -std=c++11 -L ../libs -I../includes ../testers/$1 -o ../testers_bin/$1 -lexecution
