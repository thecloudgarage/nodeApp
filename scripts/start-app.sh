#!/bin/bash
cd /home/ec2-user/myapp
sudo killall node
forever stopall
forever start ./bin/www