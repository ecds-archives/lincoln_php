#!/bin/tcsh

## A simple script to convert an sgml tei file to an xml files.
## Taken directly from the following url:
## http://www.tei-c.org/Activities/MI/miw03d.html
##
## usage: tei2xml.sh filename.sgm

set doctype = "/home/tbhasin/sandbox/lincoln/build/dataPrep/lincoln_doctype";


## if multiple filenames are specified, run this process on all of them
foreach file ($argv)
echo Converting $file to xml
#echo Using $doctype as doctype
sed 's/\&/||/g' < $file > $$.sgm
osx -xlower -xcomment -xempty -xndata -xno-expand-external -xno-expand-internal -c/chaucer/data/dtd/linux_catalog $doctype $$.sgm > $$.xml
saxon $$.xml /home/rsutton/workarea/epoet/dataPrep/tei2tei.xsl | sed -e '/^ *$/d' -e 's/||/\&/g;' > `basename $file .sgm`.xml
## remove temporary files
rm -f $$.xml $$.sgm
## these empty files are also created; removing them
rm -f extEntities.dtf intEntities.dtf
end
