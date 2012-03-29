#!/bin/bash

# bash sript: copies all php files into a src/ folder
# and makes them to sources (foo.phps)

mkdir -p src/

for i in $(ls | grep php); do
	cp $i src/${i}s
done
