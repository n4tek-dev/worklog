# worklog

## Instalacja

1. Sklonuj repozytorium:

```bash
git clone https://github.com/n4tek-dev/worklog.git
cd worklog
```

2. Skopiuj plik `.env.test` do `.env`:

```bash
cp .env.test .env
```

3. Zainstaluj zależności za pomocą Composer:

```bash
composer install
```

4. Przejdź do folderu projektu i uruchom kontenery Docker:

```bash
docker-compose up -d
```

5. Wejdź do kontenera aplikacji:

```bash
docker-compose exec php bash
```

6. Wykonaj migrację bazy danych

```bash
php artisan migrate
```

## Testowanie

Aby uruchomić testy jednostkowe, użyj następującego polecenia w kontenerze:

```bash
php artisan test
```

## API Endpoints

### Create Employee
Method: POST
Path: /api/employees
Request Parameters (JSON):
- first_name: Employee's first name.
- last_name: Employee's last name.

### Register Work Time
Method: POST
Path: /api/work-times
Request Parameters (JSON):
- employee_id: Employee ID.
- start_time: Start time in YYYY-MM-DD HH:MM:SS format.
- end_time: End time in YYYY-MM-DD HH:MM:SS format.
- start_day: Start day in YYYY-MM-DD format.

### Daily Work Summary
Method: GET
Path: /api/work-times/summary/day
Query Parameters:
- employee_id: Employee ID.
- date: Date in YYYY-MM-DD format.

### Monthly Work Summary
Method: GET
Path: /api/work-times/summary/month
Query Parameters:
- employee_id: Employee ID.
- date: Month in YYYY-MM format.
