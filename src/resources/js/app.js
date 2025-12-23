import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const likeButtons = document.querySelectorAll('.like-button');

    likeButtons.forEach(button => {
        button.addEventListener('click', async () => {
            const liked = button.dataset.liked === 'true';
            const url = liked ? button.dataset.unlikeUrl : button.dataset.url;
            const method = liked ? 'DELETE' : 'POST';

            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const res = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                    },
                });
                const data = await res.json();

                if (data.status === 'success') {
                    button.dataset.liked = data.liked ? 'true' : 'false';
                    button.querySelector('.like-icon').setAttribute('fill', data.liked ? 'red' : 'none');
                    button.querySelector('.like-count').textContent = data.count;
                }
            } catch (err) {
                console.error(err);
            }
        });
    });
});
