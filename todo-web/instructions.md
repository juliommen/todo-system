## Sistema de Gerenciamento de Tarefas

Você deve criar um módulo simples de **gerenciamento de tarefas** (to-do list) que consuma API local para gerenciar tarefas.

### Especificações da API

URL base da API: http://localhost:8080/api/v1
Métodos disponíveis:

- GET /tasks - lista todas as tarefas
- POST /tasks - cria uma nova tarefa (body: { title: string, description: string })
- PATCH /tasks/:id - atualiza uma tarefa (body: { status: 'completed' | 'pending' })
- DELETE /tasks/:id - deleta uma tarefa

### Funcionalidades da Interface

1. **Interface visual** que seja agradável e profissional
2. **Lista de tarefas** com status visual (concluída/pendente)
3. **Formulário para criar nova tarefa**
4. **Botão para marcar/desmarcar** tarefa como concluída
5. **Deletar tarefa**
6. **Estados de carregamento** (exemplo: loading, erro)

### Requisitos Técnicos Obrigatórios

- Use **React 18** com **TypeScript**
- Use Redux como ferramenta de gerenciamento de estado global
- Use Clean Architecture para organizar o código
- Crie componentes **reutilizáveis** (ex: Card, Button, Input)
- Implemente **tratamento de erros** da API (exiba mensagens ao usuário)
- Use **Tailwind CSS** para estilização
- Use **Lucide Icons** para ícones
- Configure o projeto com **Vite**
- **Atenção aos detalhes de UX** e qualidade visual
