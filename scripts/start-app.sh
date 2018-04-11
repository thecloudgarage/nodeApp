#!/bin/bash
cd /home/ec2-user/myapp
killall node
forever stopall
forever start ./bin/www