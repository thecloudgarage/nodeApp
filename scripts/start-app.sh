#!/bin/bash
cd /home/ec2-user/myapp
killall node
forever start ./bin/www