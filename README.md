# Dodo Real-Time Notifications (Laravel 11)

A real-time notification system built with Laravel 11, providing API endpoints, WebSocket broadcasting, and a minimal demo frontend.

## Tech Stack
- Backend: Laravel 11 (API-only)
- Authentication: Laravel Sanctum (token-based)
- Real-time: Laravel Reverb (WebSockets)
- Notifications: Laravel Notifications
- Database: MySQL
- Frontend: Vite + Laravel Echo
- Queue: Database (for broadcasting notifications)

## Architecture Overview
- API handles user authentication and notification management.
- Notifications are stored in the database and broadcasted in real time.
- Each user has a private WebSocket channel: `App.Models.User.{id}`.
- Queue worker processes notifications and broadcasts asynchronously.
- Reverb manages WebSocket connections for instant delivery.

## Setup Instructions

### Backend
1. Clone the repository: git clone https://github.com/KofiLeslie/dodo-notifications

2. Install PHP dependencies: composer install
    
3. Copy `.env.example` and generate app key:    
    cp .env.example .env
    php artisan key:generate
    
4. Configure database and Reverb variables in `.env`.
5. Run database migrations:    
    php artisan migrate
    
6. Serve the app:    
    php artisan serve
    

### Frontend
1. Install Node dependencies:    
    npm install
    
2. Build assets or start dev server:    
    npm run build    # production
    npm run dev      # development
    

### Real-Time Services
- Start WebSocket server:    
    php artisan reverb:start
    
- Start queue worker:    
    php artisan queue:work
    

## Running the Project
- Serve backend: `php artisan serve`
- Queue worker: `php artisan queue:work`
- WebSocket server: `php artisan reverb:start`
- Vite dev server: `npm run dev`

## API Endpoints

### Authentication
- Register: `POST /api/register`
- Login: `POST /api/login`

### Notifications
- Send Notification: `POST /api/notifications`
- Mark Notification as Read: `PATCH /api/notifications/{id}/read`
- Mark All as Read: `POST /api/notifications/read-all`
- Fetch Unread Notifications: `GET /api/notifications`

## Demo Page
- Open `resources/views/welcome.blade.php`.
- Enter your API token and User ID.
- Connect to real-time notifications via WebSocket.
- Observe incoming notifications and mark them as read.

## Testing
- Use Postman or curl to test endpoints.
- Frontend demo reflects real-time notifications.
- Unit & feature tests are included under `tests/Feature`.

## Trade-offs & Limitations
- Database queue used for simplicity.
- No Redis pub/sub for high-volume real-time broadcasting.
- Minimal demo frontend for testing purposes only.

## Possible Improvements
- Switch to Redis queue + broadcast for better performance.
- Implement read receipts visible to sender.
- Add notification grouping.
- Use Node.js WebSocket microservice for ultra-scale scenarios.
- Consider Laravel Octane + Swoole for faster request handling.

## Scalability Thoughts
- Horizontal scaling behind a load balancer supported by Reverb.
- Redis for queue + pub/sub would improve throughput.
- Notification system can be separated into a microservice for high-load apps.
