#!/bin/bash

users=("Ella" "Janne Kallunki" "Mika Tuunanen" "Risto Salo" "Ville Valkonen")
#users=$(git log --pretty="%an," |sort |uniq)

printf "# Name %-10s commit count %5s chg+ins+del %5s avg chnges per commit\n" "" "" ""
for i in ${!users[*]}
do
	metricscount=$(git log --shortstat --oneline --author="${users[$i]}" |grep -E "file(s)? changed" | \
		awk '{changes+=$1; inserts+=$4 < 10000; deletes+=$6} END {print NR, changes+inserts+deletes}')
	commitcount=$(echo ${metricscount}| cut -d " " -f 1)
	commitstats=$(echo ${metricscount}| cut -d " " -f 2) # changes, inserts, deletes
	avg_changes_per_commit=$((${commitstats} / ${commitcount}))
	printf "%-25s %s %3d %-10s %6d %-19s %7.2f\n" "\"${users[$i]}\"" "" "${commitcount}" "" \
		"${commitstats}" "" "${avg_changes_per_commit}"
done

# Snurpan kohdalla erikoistapaus
metricscount=$(git log --shortstat --oneline --author="Joni" --author="Snurppa" |grep -E "file(s)? changed" | \
	awk '{changes+=$1; inserts+=$4; deletes+=$6} END {print NR, changes+inserts+deletes}')
commitcount=$(echo ${metricscount}| cut -d " " -f 1)
commitstats=$(echo ${metricscount}| cut -d " " -f 2) # changes, inserts, deletes
avg_changes_per_commit=$((${commitstats} / ${commitcount}))
printf "%-25s %s %3d %-10s %6d %-19s %7.2f\n" "\"Snurppa\"" "" "${commitcount}" "" \
	"${commitstats}" "" "${avg_changes_per_commit}"

