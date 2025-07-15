# 🚀 Projeto Seletiva — API Laravel 5 + Frontend Angular 17

Este repositório contém o **teste técnico full-stack**, com **Laravel 5 (PHP 7)** no backend e **Angular 17** no frontend.

---

## ✅ Tecnologias Utilizadas

### 🗄️ Backend
- **PHP 7.x**
- **Laravel 5.x**
- **MySQL**
- **JWT** (Autenticação)
- **Postman** (Testes de API)

### 💻 Frontend
- **Angular 17**
- **TypeScript**
- **Tailwind CSS**

---

## 📂 Estrutura do Projeto
/teste-full-stack-api # Backend (Laravel)
/teste-full-stack-web # Frontend (Angular)


---

## ⚙️ Rodar Projeto

### 🗄️ Backend — Laravel

```bash
# Acesse a pasta do backend
cd teste-full-stack-api

# Crie o banco de dados com o nome 'testeFullStack' no seu MySQL

# Instale as dependências
composer install

# Gere a chave da aplicação
php artisan key:generate

# Rode as migrations
php artisan migrate

# Rode os seeders
php artisan db:seed
```

### 💻 Frontend — Angular

```bash
# Acesse a pasta do frontend
cd teste-full-stack-web

# Instale as dependências
npm install

# Rode a aplicação Angular
ng serve --open
```

