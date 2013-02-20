reset
#set logscale y
set terminal png size 600,600
set output "author_stats.png"
set ylabel "Commits"
set yrange [0:110]
set ytics auto
set grid
set xlabel "Author"
set xtics nomirror rotate by -45
set style fill solid
set boxwidth 0.5
set multiplot layout 2,1
set style line 1 lc rgb "red"
set style line 2 lc rgb "blue"

set ytics 10
plot 'authstats.txt' using 2:xtic(1) with boxes title "Author metrics"
set xlabel "Author"
set ylabel "Avg. chgs/commit"
set style fill solid
set boxwidth 0.5
set ytics auto
set yrange [0:220]
plot 'authstats.txt' using 4:xtic(1) with boxes ls 2 title "Avg. changes per commit"

unset multiplot
