#!/bin/sh

git log --pretty=format:'%cd' --date=short >gengraph/commitdates.txt &&
cd gengraph &&
./commitgen.py >commitlog.txt &&
gnuplot commits.gnu
if [ $? -ne 0 ]; then
	echo "Failed to generate gnuplot graph!"
	exit 1;
fi
echo "Success"

