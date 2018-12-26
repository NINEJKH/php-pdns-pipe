#!/usr/bin/env bash

set -e

screen -S pdns-pipe -p 0 -X stuff "$(echo -e "${1}")"
