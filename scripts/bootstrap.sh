
copy_env() {
  if [ ! -f "$1/.env" ] && [ -f "$1/.env.example" ]; then
    echo "Criando $1/.env a partir de .env.example"
    cp "$1/.env.example" "$1/.env"
  fi
}

copy_env .
copy_env todo-api
copy_env todo-web