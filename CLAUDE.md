# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Restaurant management/ERP web application built on **Yii2 PHP framework**. Handles products, arrivals, sales, inventory, debts, contractors, clients, and reporting.

## Development Commands

**Run the application**: Serve `web/` as document root (e.g., via OSPanel). Entry point is `web/index.php`.

**Console commands** (Yii CLI):
```bash
php yii migrate          # Run database migrations
php yii migrate/create   # Create new migration
php yii <command>/<action>
```

**Dependency management**:
```bash
php composer.phar install
php composer.phar update
```

**Tests** (Codeception):
```bash
# Requires test DB: create `yii2_basic_tests` and apply migrations
codecept run             # All tests
codecept run unit
codecept run functional
codecept run acceptance
codecept run --coverage-html --coverage-xml
```

## Architecture

### MVC Structure
- **`controllers/`** (31 files) — extend `yii\web\Controller`, follow standard CRUD actions (`actionIndex`, `actionView`, `actionCreate`, `actionUpdate`, `actionDelete`). Each controller sets `$layout` to specify its layout template.
- **`models/`** (68 files) — extend `yii\db\ActiveRecord`, one per DB table. Search models follow naming `ModelNameSearch.php` and handle GridView filtering/sorting.
- **`views/`** — organized by controller name. `views/layouts/` contains `main.php`, `admin.php`, `sell.php`, `start.php`.

### Routing
Pretty URLs enabled, no `index.php` in URL. Rules in `config/web.php`:
- `<controller>/<id:\d+>` → view
- `<controller>/<action>` → dynamic
- `<controller>/<action>/<id:\d+>` → action with ID

### Authentication
User identity: `app\models\Users`. Auto-login enabled. Passwords hashed via `Yii::$app->security->generatePasswordHash()`.

### Key Configuration Files
| File | Purpose |
|------|---------|
| `config/web.php` | Web app config (URL rules, components, modules) |
| `config/db.php` | MySQL connection (host: localhost, dbname: restoran) |
| `config/console.php` | CLI app config |
| `config/params.php` | App-level parameters |

### Frontend Stack
Bootstrap + jQuery + jQuery UI. Primary widget libraries:
- **Kartik**: GridView, DatePicker, Select2, DateRange, FieldRange
- **unclead/yii2-multiple-input**: Multi-row inputs
- **kartik-v/yii2-mpdf**: PDF generation

### Notifications
`components/PushAll.php` integrates with PushAll.ru for push notifications.

### Email
SwiftMailer configured with `useFileTransport: true` by default — emails are written to `runtime/mail/`, not sent.

## Multi-Agent Development Framework

The project includes `.claude/AGENTS_FRAMEWORK.md` (in Russian) documenting a team-based multi-agent workflow with:
- Agent roles: analysis-lead, dev-lead, qa-lead, ops-lead
- Kanban process with 6 Quality Gates
- Recommended models: Opus for architecture/core dev/e2e, Sonnet for coordination

## Environment Notes

- Debug mode is `true` in `web/index.php` (`YII_DEBUG = true`, `YII_ENV = 'dev'`) — review before production
- `YII_ENABLE_ERROR_HANDLER = false` and `YII_ENABLE_EXCEPTION_HANDLER = false` are set
- Database credentials are plaintext in `config/db.php`
- Gii code generator module is available in dev (generates models/controllers from DB schema)
- Cookie validation key is hardcoded in `config/web.php`
