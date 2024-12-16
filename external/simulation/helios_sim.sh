#!/bin/sh
dateval=`date`
dateval1=${dateval/IST}
echo $dateval1" ::  0.56967542  1.64874552  0.43649353  1.64068100 1 1 1" > /usr/local/sabia-ck/dataq.status
#touch plcd
touch /usr/local/sabia-ck/plcd.status

