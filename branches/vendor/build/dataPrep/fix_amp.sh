#!/bin/tcsh

## fix the non-entity & in sgml files
## usage: fix_amp.sh file1 file2 file3

set exscript = "/tmp/fix_amp";

## bare ampersand (space afterwards)
echo '1,$s/& /\&amp; /g' > $exscript;
## ampersand at the end of a line
echo '1,$s/&$/\&amp;/g' >> $exscript;
## ampersand followed by any one of comma, period, tag, or double quote
echo '1,$s/&\([,\.<"]\)/\&amp;\1/g' >> $exscript;
## FIXME: how to match for &' ?
echo "write" >> $exscript;
echo "quit" >> $exscript;

foreach file ($argv)
echo "Fixing $file";
   ex $file < $exscript;
end

rm $exscript;
