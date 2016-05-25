#!/bin/bash
g++ -x c++ -std=c++11 ../executions/$1 -o ../executions_bin/$1 > ../compilelogs/$1.txt 2>&1
