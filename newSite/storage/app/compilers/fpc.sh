#!/bin/bash
fpc -FE../executions_bin/ ../executions/$1 > ../compilelogs/$1.txt 2>&1
ec=$?
find ../executions_bin/ -type f -name "*.o" -delete
exit $ec
