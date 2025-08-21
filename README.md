# TMDB Movies App 🎬

Aplicação Laravel integrada com a API do [TMDB](https://www.themoviedb.org/) para exibir e gerenciar filmes, permitindo marcar como favoritos e armazenar no banco de dados.

---

## 🛠 Tecnologias Utilizadas

- **Backend:** Laravel 9 (PHP 8.2)
- **Frontend:** Vue.js 3 + Inertia.js
- **Banco de Dados:** MySQL 8.0 / SQLite para testes
- **API Externa:** TMDB (The Movie Database)
- **Autenticação:** Laravel Breeze
- **Testes:** PHPUnit 10, Mockery
- **Gerenciador de Dependências PHP:** Composer 2
- **Node.js:** v20 (para compilação de assets)
- **Docker:** Docker 24.x e Docker Compose 2.x

---

## 🚀 Como rodar o projeto localmente com Docker

### 1. Clonar o repositório
```bash
git clone https://github.com/daniloliveira-dev/tmdb-movies-app.git
cd tmdb-movies-app
```

### 2. Copiar o arquivo `.env.example` para `.env`
```bash
cp .env.example .env
```

### 3. Configurar variáveis de ambiente
- Defina a chave da API do TMDB em `TMDB_API_KEY`.
- Configure o banco de dados conforme o `docker-compose.yml` abaixo.

### 4. docker-compose.yml de exemplo
```yaml
version: '3.8'

services:
  app:
    image: php:8.2-fpm
    container_name: tmdb-app
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
    ports:
      - "8000:8000"
    depends_on:
      - db
    command: php artisan serve --host=0.0.0.0 --port=8000

  db:
    image: mysql:8.0
    container_name: tmdb-db
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: tmdb
      MYSQL_USER: user
      MYSQL_PASSWORD: password
    ports:
      - "3306:3306"
    volumes:
      - db_data:/var/lib/mysql

volumes:
  db_data:
```

### 5. Subir os containers
```bash
docker-compose up -d --build
```

### 6. Acessar o container Laravel
```bash
docker exec -it tmdb-app bash
```

### 7. Gerar a chave da aplicação Laravel
```bash
php artisan key:generate
```

### 8. Acessar a aplicação
Abra no navegador: [http://localhost:8000](http://localhost:8000)

---

## 🗄️ Como importar o banco de dados

### Opção 1: Restaurar dump SQL
Caso exista um arquivo `dump.sql`:
```bash
docker exec -i tmdb-db mysql -u root -proot tmdb < dump.sql
```

### Opção 2: Rodar migrations e seeds
```bash
php artisan migrate --seed
```

---

## 📂 Onde está implementado o CRUD de filmes favoritos

- **Rotas:** `routes/web.php`
- **Controller:** `app/Http/Controllers/MovieController.php`
- **Model:** `app/Models/Movie.php`
- **Views (Inertia/Blade):** `resources/js/Pages/Favorites.vue`

---

## ✅ Como testar a aplicação

### Testes automatizados
```bash
php artisan test
```
ou
```bash
vendor/bin/phpunit
```

### Testes manuais
1. Acesse a interface web em [http://localhost:8000](http://localhost:8000)
2. Navegue até a seção de favoritos
3. Adicione e remova filmes, verificando a persistência no banco

---

## 🔑 API Key do TMDB

1. Crie uma conta no [TMDB](https://www.themoviedb.org/)
2. Vá até [API Settings](https://www.themoviedb.org/settings/api)
3. Gere sua chave e adicione no `.env`:
```
TMDB_API_KEY=suachaveaqui
```

---

## 🎨 Como subir o frontend separado (Vue.js ou React.js)

Se o frontend estiver em **Vue.js** ou **React.js** dentro de `frontend/`:
```bash
cd frontend
npm install
npm run dev
```
A aplicação ficará disponível em: [http://localhost:3000](http://localhost:3000)

---

## 📌 Observações
- Certifique-se de ter **Docker** e **Docker Compose** instalados.
- O backend (Laravel) roda em `http://localhost:8000`.
- O frontend (Vue/React) roda em `http://localhost:3000` se separado.

---

👨‍💻 Desenvolvido por [Danilo Oliveira](https://github.com/daniloliveira-dev)

