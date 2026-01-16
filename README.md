To Serve run: php artisan serve --port=1090
Run queue worker: php artisan queue:work
Start WebSocket server: php artisan reverb:start
Run Vite dev server: npm run dev

# Dodo Real-Time Notifications (Laravel 11)

## Tech Stack
- Laravel 11 (API-only)
- Laravel Sanctum (token auth)
- Laravel Reverb (WebSockets)
- Laravel Notifications
- MySQL
- Vite + Laravel Echo
- Queue: Database

## Architecture Overview
- API handles auth & notification creation
- Notifications stored in DB and broadcast in real time
- Private channels per user: App.Models.User.{id}
- Queue worker processes broadcasts
- Reverb handles WebSocket connections

## Setup Instructions

### Backend
1. Clone repo
2. composer install
3. cp .env.example .env
4. php artisan key:generate
5. Configure DB & Reverb vars
6. php artisan migrate
7. php artisan serve --port=1090

### Frontend
1. npm install
2. npm run build (or npm run dev)

### Real-Time Services
- php artisan reverb:start
- php artisan queue:work

## API Endpoints

### Register
POST /api/register

### Login
POST /api/login

### Send Notification
POST /api/notifications

### Mark as Read
PATCH /api/notifications/{id}/read

## Testing
- Use Postman or curl
- Open demo page and connect via token
- Send notifications and observe real-time updates

## Trade-offs & Limitations
- Database queue used for simplicity
- No Redis pub/sub yet
- Demo UI is minimal

## Possible Improvements
- Redis queue + broadcast
- Read receipts to sender
- Notification grouping
- Node.js WebSocket microservice for ultra-scale
- Octane + Swoole

## Scalability Thoughts
- Reverb scales horizontally behind load balancer
- Redis for queue + pub/sub
- Separate notification service

