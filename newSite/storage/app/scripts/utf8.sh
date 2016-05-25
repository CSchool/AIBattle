#!/bin/bash
encoding=`file -bi $1 | sed -e 's/.*[ ]charset=//'`
echo $encoding
