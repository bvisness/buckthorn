#!/bin/bash

echo "Username: "
read USR

ssh "$USR"@wiebe.mathcs.bethel.edu -L 3307:localhost:3306 -N
