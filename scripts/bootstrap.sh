#!/usr/bin/env bash
set -e

copy_env() {
  local dir="$1"

  if [ ! -f "$dir/.env" ] && [ -f "$dir/.env.example" ]; then
    echo "Criando $dir/.env a partir de .env.example"
    cp "$dir/.env.example" "$dir/.env"
  fi
}

copy_env "."
copy_env "todo-api"
copy_env "todo-web"