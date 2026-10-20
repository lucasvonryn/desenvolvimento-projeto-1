# Medições Wi-Fi

Aplicação web para cadastrar medições de sinal de internet (2,4 GHz e 5 GHz) e gerar relatórios com tabelas, médias, rankings, alertas e gráficos.

Desenvolvida para a disciplina **Desenvolvimento de Projeto 1** (UTFPR — Campus Ponta Grossa), a partir da análise de rede feita na empresa Nataq.

## Stack

- **Backend:** Laravel (PHP) + SQLite
- **Frontend:** Blade + Tailwind CSS + Vite
- **Gráficos:** Chart.js
- **PDF:** DomPDF

Sem autenticação (uso local / acadêmico).

## Funcionalidades

- CRUD de empresas, cômodos, equipamento e medições
- Relatórios filtráveis por dias (médias, comparativo entre bandas, evolução temporal, ranking, alertas)
- Exportação do relatório em PDF
- Seed com dados da Nataq (medições de 04/09/2026 e 08/09/2026)

## Como rodar

Requisitos: PHP 8.3+, Composer, Node.js 20+.

```bash
composer install
cp .env.example .env   # se ainda não existir .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan storage:link

npm install
npm run build          # ou: npm run dev

php artisan serve
```

Acesse: [http://127.0.0.1:8000/empresas](http://127.0.0.1:8000/empresas)

## Estrutura rápida

| Pasta / arquivo | Função |
|-----------------|--------|
| `app/Models/` | Company, Room, Equipment, Measurement |
| `app/Http/Controllers/` | CRUDs e relatórios |
| `app/Services/ReportAggregator.php` | Cálculos do relatório |
| `database/seeders/NataqSeeder.php` | Pré-cadastro da Nataq |
| `resources/views/` | Telas Blade |
