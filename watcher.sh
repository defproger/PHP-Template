#!/bin/bash

while inotifywait -e modify,create,delete,move ./src; do
  composer dump-autoload -o
done