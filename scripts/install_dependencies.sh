#!/bin/bash
yum groupinstall -y "Web Server" "PHP Support"
yum install -y php-mysql