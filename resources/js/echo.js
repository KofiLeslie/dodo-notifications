import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Standard function
function initEcho(token, userId) {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: Number(import.meta.env.VITE_REVERB_PORT),
        wssPort: Number(import.meta.env.VITE_REVERB_PORT),
        forceTLS: false,
        enabledTransports: ['ws'],
        authEndpoint: `${import.meta.env.VITE_API_URL}/broadcasting/auth`,
        auth: {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            },
        },
    });

    loadNotifications(token);

    window.Echo.private(`App.Models.User.${userId}`)
        .listen(
            '.Illuminate\\Notifications\\Events\\BroadcastNotificationCreated',
            (e) => {
                console.log('New notification received:', e.notification ?? e);
                addNotificationToUI(e.notification ?? e);
            }
        );
}

function showLoader(title = 'Processing...') {
    Swal.fire({
        title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function addNotificationToUI(notification) {
    const list = document.getElementById('notifications');
    if (!list) return;

    const li = document.createElement('li');
    li.className = 'list-group-item d-flex justify-content-between align-items-center unread';
    li.dataset.id = notification.id;

    li.innerHTML = `
        <span>
            <span class="badge bg-primary me-2">${notification.type ?? 'info'}</span>
            ${notification.message}
        </span>
        <button class="btn btn-sm btn-success"
            onclick="markAsRead('${notification.id}')">
            Mark as read
        </button>
    `;

    list.prepend(li);
}

window.markAsRead = function (id) {
    const token = document.getElementById('token').value;

    Swal.fire({
        title: 'Mark as read?',
        text: 'This notification will be marked as read.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark it',
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        showLoader('Marking as read...');

        try {
            await fetch(`/api/notifications/${id}/read`, {
                method: 'PATCH',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                }
            });

            const li = document.querySelector(`li[data-id="${id}"]`);
            if (li) li.remove();

            Swal.fire({
                icon: 'success',
                title: 'Marked as read',
                timer: 1000,
                showConfirmButton: false
            });

        } catch (e) {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Unable to mark notification as read.',
            });
        }
    });
};

window.markAllAsRead = function () {
    const token = document.getElementById('token').value;

    Swal.fire({
        title: 'Mark all notifications as read?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, mark all',
    }).then(async (result) => {
        if (!result.isConfirmed) return;

        showLoader('Marking all as read...');

        try {
            await fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    Authorization: `Bearer ${token}`,
                    Accept: 'application/json',
                }
            });

            document.getElementById('notifications').innerHTML = '';

            Swal.fire({
                icon: 'success',
                title: 'All notifications marked as read',
                timer: 1200,
                showConfirmButton: false
            });

        } catch (e) {
            Swal.fire({
                icon: 'error',
                title: 'Failed',
                text: 'Unable to mark notifications as read.',
            });
        }
    });
};

async function loadNotifications(token) {
    showLoader('Loading notifications...');

    try {
        document.getElementById('notifications').innerHTML = '';

        const res = await fetch('/api/notifications', {
            headers: {
                Authorization: `Bearer ${token}`,
                Accept: 'application/json',
            }
        });

        const data = await res.json();

        data.forEach(n => addNotificationToUI(n));

        Swal.close();

    } catch (e) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load notifications.',
        });
    }
}

window.initEcho = initEcho;
