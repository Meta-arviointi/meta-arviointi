#!/bin/sh

cd gengraph &&
./author_stats.sh |sort >authstats.txt &&
gnuplot gen_author_stat_graphs.gnu
if [ $? -ne 0 ]; then
	echo "Failed to generate author graph!"
	exit 1;
fi
echo "Success"

