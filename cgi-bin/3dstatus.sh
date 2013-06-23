#!/bin/sh

cd /usr/share/makerbot
. virtualenv/bin/activate
python conveyor_cmdline_client.py jobs 2>/dev/null | head -n 1 | sed 's/^conveyor: INFO: //' | sed 's/ u/ /g' | sed 's/{u/{/g' | sed "s/'/\"/g" | sed 's/None/\"None\"/g'

