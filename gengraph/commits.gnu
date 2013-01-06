# git log --pretty=format:'%cd' --date=short >commitdates.txt
reset
#set logscale y
set terminal png size 600,400
set output "commits.png"
set ylabel "Commits"
set grid
set xdata time
# 2012-12-16
set timefmt "%Y-%m-%d"
set format x "%Y-%m"
set xlabel "Date"
set xrange ["2012-09" : "2013-01"]
set xtics nomirror rotate by -90
set yrange [0 : 12]
# Colours
# 1=red 2=grn 3=blue

#plot 'commitlog.txt' using 1:2 with linespoints lt 1 title "Commits"
#plot 'commitlog.txt' using 1:2 with linespoints lt 1 smooth csplines title "Commits"
plot 'commitlog.txt' using 1:2 linestyle 7 lt 2 lc 1 title "Commit count", \
     'commitlog.txt' using 1:2 with linespoints lc 3 smooth csplines title "Commits trend", \
     'commitlog.txt' using 1:2 with linespoints lc 2 lw 2 smooth bezier title "Commits trend 2"

