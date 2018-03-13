#!/bin/bash
service nginx restart > /var/log/restartPackages.out 2>&1
service php-fpm restart > /var/log/restartPackages.out 2>&1