# TMDB Movies App 🎬

Aplicação Laravel integrada com a API do [TMDB](https://www.themoviedb.org/) para exibir e gerenciar filmes, permitindo marcar como favoritos e armazenar no banco de dados.

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
- Configure o banco de dados conforme o `docker-compose.yml`.

### 4. Subir os containers com Docker Compose
```bash
docker-compose up -d --build
```

### 5. Acessar o container Laravel
```bash
docker exec -it tmdb-app bash
```

### 6. Gerar a chave da aplicação Laravel
```bash
php artisan key:generate
```

### 7. Acessar a aplicação
Abra no navegador: [http://localhost:8000](http://localhost:8000)

---

## 🗄️ Como importar o banco de dados

### Opção 1: Restaurar dump SQL
Caso exista um arquivo `dump.sql`:
```bash
docker exec -i tmdb-db mysql -u root -p tmdb < dump.sql
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
- **Views (Inertia/Blade):** `resources/js/Pages/Favorites.vue` (ou similar)

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

## 🎨 Como subir o frontend separado (caso esteja desacoplado)

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
