# README.md

## Setup
cp .env.example .env
docker compose up -d --build
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate


## Auth
curl -X POST http://localhost:8080/api/auth/register \
 -H 'Content-Type: application/json' \
 -d '{"name":"Alice","email":"alice@example.com","password":"password123"}'

curl -X POST http://localhost:8080/api/auth/login \
 -H 'Content-Type: application/json' \
 -d '{"email":"alice@example.com","password":"password123"}'

## Projects
curl -H "Authorization: Bearer <TOKEN>" http://localhost:8080/api/projects
curl -X POST -H "Authorization: Bearer <TOKEN>" -H "Content-Type: application/json" \
 -d '{"name":"My Project"}' http://localhost:8080/api/projects

## Tasks (with filtering & pagination)
curl -H "Authorization: Bearer <TOKEN>" \
 "http://localhost:8080/api/projects/1/tasks?status=todo&due_from=2025-09-01&search=report&page=1"

## Comments
curl -X POST -H "Authorization: Bearer <TOKEN>" -H "Content-Type: application/json" \
 -d '{"body":"Nice work!"}' http://localhost:8080/api/projects/1/tasks/2/comments

## Notifications
curl -H "Authorization: Bearer <TOKEN>" http://localhost:8080/api/notifications/unseen
curl -X POST -H "Authorization: Bearer <TOKEN>" http://localhost:8080/api/notifications/<id>/mark-read

## Notes
Repository + Strategy + Observer patterns applied.
Redis-backed cache for task listings.
Queue: notifications are ShouldQueue and processed by the queue service.
Rate limits: auth (10/min), write ops (60/min), notifications (30/min).