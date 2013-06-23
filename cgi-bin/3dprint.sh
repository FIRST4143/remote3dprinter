#!/bin/sh

echo $$ > /var/www/status/pid.txt
echo "file name: " $1 >/var/www/status/clientoutput.txt 2>&1
cd /usr/share/makerbot
. virtualenv/bin/activate
python conveyor_cmdline_client.py print $1 >>/var/www/status/clientoutput.txt 2>&1 &
#rm /var/www/status/pid.txt

