#!/bin/sh

cd /usr/share/makerbot
. virtualenv/bin/activate
python conveyor_cmdline_client.py cancel $1 > /dev/null 2>&1
rm /var/www/status/pid.txt > /dev/null 2>&1

