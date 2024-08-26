#!/usr/bin/env bash

set -o errexit

docker compose run --rm schnoop composer style-check
