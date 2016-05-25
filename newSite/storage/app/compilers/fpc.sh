#!/bin/bash
fpc -Mdelphi -FE../executions_bin/ ../executions/$1 > ../compilelogs/$1.txt 2>&1
fatalCount=`grep -ic fatal ../compilelogs/$1.txt`
find ../executions_bin/ -type f -name "*.o" -delete
exit $fatalCount
