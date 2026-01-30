# Instalação

1. Build e up de todos os serviços em modo desenvolvimento:

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

---

# Projeto

## Todo API

Documento da estrutura de dados usada para representar uma _tarefa_ no banco de dados (exemplo de objeto):

```json
{
  "id": 1,
  "title": "Comprar leite",
  "description": "Ir ao supermercado e comprar 2 litros de leite desnatado",
  "status": "completed",
  "created_at": "2026-01-30T10:00:00Z",
  "updated_at": "2026-02-01T23:59:00Z"
}
```

Campo / tipo (descrição):

- `id` (integer): identificador único da tarefa.
- `title` (string): título curto da tarefa.
- `description` (string): descrição, detalhes adicionais.
- `created_at` (string ISO8601): timestamp de criação.
- `updated_at` (string ISO8601): timestamp de atualização.
- `status` (string enum): estado da tarefa, valores possíveis: `pending`, `completed`.

Docker / desenvolvimento (escolhas e justificativa):

- Base da imagem: usa `php:8.2-cli` definido em [todo-api/Dockerfile.dev](todo-api/Dockerfile.dev). Escolhi PHP 8.2 pela compatibilidade com as dependências e por ser a versão alvo do projeto.
- Extensões: instala `pdo` e `pdo_mysql` para acesso ao MySQL (via Doctrine DBAL/ORM).
- Expose/porta: a aplicação roda com o servidor embutido em `0.0.0.0:8080` (padrão para desenvolvimento). Arquivo: [todo-api/Dockerfile.dev](todo-api/Dockerfile.dev).
- Volumes em `docker-compose.dev.yml`: mapeia `./todo-api:/var/www/html` para permitir hot-reload/desenvolvimento, e monta `/var/www/html/vendor` separadamente para evitar sobrescrever dependências já instaladas.
- Variáveis de ambiente: `env_file` aponta para `./todo-api/.env` para segregar segredos/configurações de ambiente.
- Banco: usa `mysql:8.0` em [docker-compose.dev.yml](docker-compose.dev.yml) com volume `db_data` e `healthcheck` para garantir disponibilidade antes de subir o serviço da API.

_Nota_: Setup Docker voltado exclusivamente para ambiente de desenvolvimento.

---

## Todo Web

Estrutura principal de componentes e responsabilidades (resumo):

```
src/
├── main.tsx                    # ponto de entrada do Vite
├── app/
│   ├── App.tsx                 # roteamento e composição global
│   └── store.ts                # configuração do Redux store
├── pages/
│   └── TasksManager.tsx        # página principal que monta o gerenciador de tarefas
├── features/
│   └── tasks/
│       ├── api/tasks.api.ts    # chamadas HTTP centralizadas para a API
│       ├── components/*        # componentes específicos de tasks
│       ├── tasks.types.ts      # tipos TS relacionados a tasks
│       └── tasks.slice.ts      # state + reducers + thunks (RTK)
├── shared/
│   ├── api/http.ts             # cliente HTTP configurado para a API
│   ├── components/
│   │   ├── core/               # componentes básicos estilizados
│   │   │   ├── Input.tsx       # input controlado estilizado reutilizável
│   │   │   ├── Button.tsx      # botão estilizado reutilizável
│   │   │   └── Spinner.tsx     # spinner estilizado reutilizável
│   │   └── ui/                 # componentes de UI mais elaborados
│   │       ├── AlertDialog.tsx # diálogo de alerta Radix UI reutilizável
│   │       ├── Badge.tsx       # badge estilizado reutilizável
│   │       └── ErrorAlert.tsx  # alerta de erro reutilizável
│   └── hooks/
│       ├── useAppDispatch.ts   # hook tipado para dispatch do Redux
│       └── useAppSelector.ts   # hook tipado para selector do Redux
└── lib/
		└── utils.ts                # utilitários compartilhados
```

Docker / desenvolvimento (escolhas e justificativa):

- Base da imagem: `node:20-alpine` em [todo-web/Dockerfile.dev](todo-web/Dockerfile.dev) para builds leves e compatibilidade com Node 20.
- Volumes: `./todo-web/:/app:delegated` para permitir edição de código local e `- /app/node_modules` para isolar dependências instaladas no container.
- Variáveis/flags: define `CHOKIDAR_USEPOLLING=true` no `docker-compose.dev.yml` para melhorar a detecção de mudanças em alguns ambientes de container.
- Comando dev: `npm run dev -- --host 0.0.0.0` expõe o servidor Vite para a máquina host.

_Nota_: Setup Docker voltado exclusivamente para ambiente de desenvolvimento.

---

## Visão geral do sistema

- Tecnologias utilizadas:
  - Backend: PHP 8.2, Slim 4, Doctrine ORM/DBAL, Monolog, Composer
  - Banco de dados: MySQL 8.0 (em container)
  - Frontend: React 18, TypeScript, Vite, Redux Toolkit, Tailwind CSS
  - Ferramentas: Docker, Docker Compose, PHPUnit (tests), ESLint, Tailwind

- Decisões de design
  - Arquitetura baseada em features no frontend para facilitar escalabilidade e isolamento de domínio.
  - Uso de Docker apenas para desenvolvimento, visando ambiente reprodutível sem acoplar decisões de produção.
  - API com Clean Architecture para manter separação de responsabilidades, testabilidade e facilitar manutenção.
  - API com Slim 4 + Doctrine para manter baixo acoplamento e clareza de domínio.

---

## Observações finais

- Tempo aproximado de desenvolvimento: ~20 horas
