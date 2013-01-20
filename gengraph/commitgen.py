#!/usr/bin/env python
# -*- coding: utf-8 -*-

data = []
with open("commitdates.txt", "r") as lines:
    data = [line for line in lines]

commitcount = 0
for i in range(1, len(data)):
    data[i - 1] = data[i - 1].rstrip('\n')
    data[i] = data[i].rstrip('\n')
    prevdate = data[i - 1].split('-')
    curdate = data[i].split('-')

    # Same year, month and day
    if prevdate == curdate:
        commitcount += 1
        #print "%s\t%s" % (data[i], "")
    else:
        commitcount += 1
        print "%s\t%s" % (data[i - 1], commitcount)
        #print "------------"
        commitcount = 0

