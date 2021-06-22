#!/usr/bin/env bash

echo "--Copy env from dist--"
if [[ ! -f ./.env.local ]]; then
    cp ./.env.dist ./.env.local
fi
if [[ ! -f ./.env ]]; then
    cp ./.env.dist ./.env
fi
