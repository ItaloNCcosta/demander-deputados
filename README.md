# Demander Deputados 🚀

Aplicação Laravel para sincronizar e exibir dados de deputados e suas despesas a partir da API aberta da Câmara dos Deputados.  
Inclui sincronização automática em background via filas (Redis + Horizon), agendamento de jobs (Scheduler) e painel de monitoramento.

---

## 📦 Tecnologias

- **Laravel 12**  
- **MySQL**  
- **Redis** (filas)  
- **Laravel Horizon** (dashboard de filas)  
- **Docker & Docker Compose**  

---

## 🔧 Pré-requisitos

- Docker (>= 20.10)  
- Docker Compose (>= 1.27)  

---

## 🚀 Passo a passo

1. **Clone o repositório**  
   ```bash
   git clone https://github.com/ItaloNCcosta/demander-deputados.git

   ou

   git clone git@github.com:ItaloNCcosta/demander-deputados.git
   cd demander-deputados
   ```

2. **Copie o `.env`**  
   ```bash
   cp .env.example .env
   ```
   Ajuste, se quiser, as credenciais MySQL ou outras variáveis.

3. **Suba os containers**  
   ```bash
   docker compose up -d --build
   ```
   Isso criará e iniciará os serviços:
   - `redis`  
   - `mysql`  
   - `laravel` (PHP-FPM)  
   - `nginx`  
   - `worker` (queue:work)  
   - `scheduler` (schedule:work)  
   - `horizon`  

4. **Instale dependências PHP**  
   ```bash
   docker exec -it demander-laravel composer install --optimize-autoloader --no-dev
   ```

5. **Gere a chave da aplicação**  
   ```bash
   docker exec -it demander-laravel php artisan key:generate
   ```

6. **Rode migrações & seeders**  
   ```bash
   docker exec -it demander-laravel php artisan migrate --force
   ```
   Se você tiver o comando de bootstrap criado (veja `app:bootstrap-data`), pode rodar:
   ```bash
   docker exec -it demander-laravel php artisan app:bootstrap-data
   ```
   Isso irá sincronizar inicialmente todos os deputados e despesas.

7. **Acesse a aplicação**  
   - **Web**: http://127.0.0.1:8080  
   - **Horizon**: http://127.0.0.1:8080/horizon  

---

## ⚙️ Gerenciamento de filas e agendamentos

- **Dashboard Horizon**  
  Verifique o status de jobs, batches e métricas:  
  `http://127.0.0.1:8080/horizon`

- **Comandos úteis**  
  ```bash
  # Listar tarefas agendadas
  docker exec -it demander-laravel php artisan schedule:list

  # Forçar execução imediata dos agendamentos
  docker exec -it demander-laravel php artisan schedule:run --verbose

  # Reiniciar workers (limpa código cache e recarrega)
  docker exec -it demander-laravel php artisan queue:restart

  # Encerrar Horizon para reiniciar com nova config
  docker exec -it demander-laravel php artisan horizon:terminate
  ```

- **Limpar filas e cache**  
  ```bash
  # Redis
  docker exec -it demander-redis redis-cli FLUSHDB

  # Failed jobs (database driver)
  docker exec -it demander-laravel php artisan queue:flush

  # Cache geral
  docker exec -it demander-laravel php artisan cache:clear
  ```

- **Parar e remover todo o setup Docker**  
  ```bash
  docker-compose down --rmi all --volumes --remove-orphans
  ```

---

## 📖 Estrutura de containers

```text
services:
  redis           # broker de fila e cache
  mysql           # banco de dados
  laravel         # PHP-FPM + app code
  nginx           # web server
  worker          # php artisan queue:work
  scheduler       # php artisan schedule:work
  horizon         # php artisan horizon
```

---

## 📝 Observações

- A sincronização adota uma abordagem híbrida:
  - **Stale-while-revalidate**: ao acessar a página, exibe dados do banco e dispara revalidação em background se estiverem “stale”.
  - **Jobs agendados**: independentemente do acesso do usuário, há schedules definidos:
    - `SyncAllDeputiesJob` rodando **a cada hora**.
    - `SyncAllDeputiesExpensesJob` rodando **a cada quinze minutos**.
- Ajuste no `.env`:  
  ```dotenv
  QUEUE_CONNECTION=redis
  CACHE_DRIVER=redis
  SESSION_DRIVER=redis
  REDIS_HOST=redis
  REDIS_PORT=6379
  ```

Pronto! Agora seu ambiente Docker está configurado para rodar automaticamente filas, agendamentos e dashboard de monitoramento. Qualquer dúvida, consulte a [documentação oficial do Laravel](https://laravel.com/docs/12.x).
