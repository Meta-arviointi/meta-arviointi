#!/usr/bin/env python

import sys

assarit = ["risto", "ville", "assarix", "assariy"]

if len(sys.argv) < 2:
    print u"Anna lkm argumenttina"
    sys.exit(1)

for i in range(int(sys.argv[1])):
    # Aaltonen;Sami;81845;Henri.T.Aaltonen@uta.fi;risto
    etunimi = "Etunimi%d" % i
    sukunimi = "Sukunimi%d" % i
    email = "email@%d.fi" % i
    vastuuop = "%s" % assarit[i % len(assarit)]
    opnro = "%05d" % i
    print "%s;%s;%s;%s;%s" % (sukunimi, etunimi, opnro, email, vastuuop) # jos haluat vastuuop mukaan
    #print "%s;%s;%s;%s" % (sukunimi, etunimi, opnro, email)

