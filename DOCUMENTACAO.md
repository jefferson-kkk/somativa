# SafeSchool - Documentacao simples

## Objetivo

Sistema Laravel para controle de alunos e solicitacoes/autorizacoes de saida escolar, usando paineis Filament por tipo de usuario.

## Tipos de usuarios

- `admin`: acessa o painel administrativo em `/admin`.
- `professor`: acessa o painel de professor em `/professor`.
- `portaria`: acessa o painel de portaria em `/portaria`.
- `usuario`: acessa o painel generico em `/usuario`.

Cada usuario deve ter o campo `role` preenchido com o mesmo identificador do painel que pode acessar.

## Funcionamento basico

O login e feito pelos paineis Filament. Depois de autenticado, o sistema verifica se o `role` do usuario corresponde ao painel acessado. Se nao corresponder, o acesso e bloqueado.

## Principais funcionalidades

- Cadastro e listagem de alunos.
- Vinculo de aluno com professor responsavel.
- Acao de autorizar saida de aluno.
- Registro de motivo, horario e status da saida.
- Paineis separados para administrador, professor, portaria e usuario.

## Estrutura principal

- `app/Models`: modelos `User`, `Student` e `ExitRequest`.
- `app/Filament/Resources/Students`: telas e configuracao do recurso de alunos.
- `app/Providers/Filament`: configuracao dos paineis Filament.
- `database/migrations`: estrutura das tabelas.
- `routes/web.php`: rota publica inicial.
- `resources`: arquivos de frontend e views.

## Como executar localmente

1. Configure o arquivo `.env`.
2. Garanta que as extensoes PHP necessarias estejam ativas, principalmente `mbstring`, `pdo_sqlite`, `xml` e `dom`.
3. Instale dependencias PHP se necessario: `composer install`.
4. Instale dependencias frontend se necessario: `npm install`.
5. Rode as migrations: `php artisan migrate`.
6. Inicie o Laravel: `php artisan serve`.
7. Em outro terminal, inicie o Vite: `npm run dev`.

## Tecnologias utilizadas

- PHP
- Laravel
- Filament
- Livewire
- SQLite
- Vite
- Tailwind CSS
