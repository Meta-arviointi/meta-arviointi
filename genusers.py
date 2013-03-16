#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys

assarit = ["risto", "ville", "assarix", "assariy"]
start, end = 0, 0

def usage():
    print u"Generoi oppilaita 10:30 väliltä:"
    print "  $ python genusers.py 10:30"
    print "  esimerkki tulosteesta: Sukunimi10;Etunimi10;00010;email@10.fi;assarix"
    print "tai generoi 20 oppilasta:"
    print "  $ python genusers.py 20"
    sys.exit(1)

if len(sys.argv) == 2: # generoitava alue mukana
    if sys.argv[1].find('-') > 0 or sys.argv[1].find(':') > 0:
        start = sys.argv[1].replace('-', ':').split(':')
        end = int(start[1]) + 1
        start = int(start[0])
    else:
        start = 0
        end = int(sys.argv[1])
else:
    usage()

for i in range(start, end):
    # Sukunimi;Etunimi;81845;etunimi.sukunimi@uta.fi;risto
    etunimi = "Etunimi%d" % i
    sukunimi = "Sukunimi%d" % i
    email = "email@%d.fi" % i
    vastuuop = "%s" % assarit[i % len(assarit)]
    opnro = "%05d" % i
    print "%s;%s;%s;%s;%s" % (sukunimi, etunimi, opnro, email, vastuuop)

