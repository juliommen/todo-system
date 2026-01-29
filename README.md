Projeto monorepo com duas aplicações: API em `todo-api` e frontend em `todo-web`.

Execução (na raiz do projeto):

1. Build e up de todos os serviços em modo desenvolvimentos:

```bash
bash scripts/bootstrap.sh
docker compose -f docker-compose.dev.yml up --build -d
```

2. Serviços expostos:

- Web: http://localhost:5173
- API: http://localhost:8080

3. Rodar testes da API:

```bash
docker compose -f docker-compose.dev.yml exec todo-api ./vendor/bin/phpunit --colors=always
```
