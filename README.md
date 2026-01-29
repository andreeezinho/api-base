# API RestFull 

API RestFull Desenvolvida com PHP puro (sem frameworks externos) com objetivo de ser utilizada como base para iniciar projetos futuros com o básico já feito.

O projeto busca implementar tecnologias e padrões que garantem a organização, escalabilidade e manutenções futuras. 

## Tecnologias, Padrões e Arquiteturas
- PHP 8.3
- Organização de Rotas Personalizadas
- Autenticação via JWT
- Composer
- DDD
- Clean Architecture
- Arquitetura Hexagonal

## Arquitetura do Projeto
A arquitetura do projeto segue princípios de **DDD**, **Clean Architecture** e **Arquitetura Hexagonal**
```
app
├── logs
├── public
└── src
    ├── Config
    ├── Domain
    │   ├── Models
    │   └── Repositories
    ├── Http
    │   ├── Controllers
    │   ├── Request
    │   └── Transformer
    ├── Infra
    │   ├── Persistence
    │   └── Services
    ├── Routers
    ├── Utils
    ├── composer.json
    ├── composer.lock
    ├── .env
    ├── index.php
```

## Funcionalidades 

- Autenticação e Segurança via JWT
- Rotas dinâmicas e personalizadas
- Sistema de logs personalizáveis
- Upload dinâmico de arquivos
- Sistema de notificação de email
- Customização de variáveis de ambiente via `.env`

## Execução do Projeto

### 1 - Clonar repositório

```bash
git clone https://github.com/andreeezinho/sistema-pdv.git
```

### 2 - Remover '.example.' de `src/.env.example`

### 3 - Inserir valores nas variáveis
Insira os valores de acordo com o seus dados
```bash
SITE_NAME='nome-api'
API_URL='http://localhost:8888'

DB_HOST='local-database'
DB_NAME='nome-database'
DB_USER='user-database'
DB_PASSWORD='senha-database'

JWT_SECRET='senha-jwt' ## Senha para personalizar o token JWT

EMAIL = 'seuemail@gmail.com' 
EMAIL_CODE = 'gfte esjt eqes qhmm'; ## Senha do SMTP que precisa cadastrar
```

### 4 - Executar o script `db.sql` para o banco de dados
```bash
mysql -u root -p api-db < db.sql
```

O script vem com um usuário padrão com todas as permissões inicialmente:

```
email: admin@admin.com
senha: password
```

### 5 - Executar projeto
```bash
php -S localhost:8888 -t ./
```