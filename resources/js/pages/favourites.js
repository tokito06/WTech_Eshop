export default function initFavouritesPage() {
    const likedGrid = document.getElementById('liked-grid');
    const likedEmpty = document.getElementById('liked-empty');
    if (!likedGrid || !likedEmpty) {
        return;
    }

    likedGrid.addEventListener('click', async event => {
        const btn = event.target.closest('.js-remove-favourite');
        if (!btn) {
            return;
        }

        const removeUrl = btn.dataset.removeUrl;
        if (!removeUrl) {
            return;
        }

        try {
            const response = await fetch(removeUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
            });

            if (!response.ok) {
                throw new Error('Failed to remove');
            }

            const col = btn.closest('[class*="col-"]');
            if (col) {
                col.style.transition = 'opacity 0.25s, transform 0.25s';
                col.style.opacity = '0';
                col.style.transform = 'scale(0.9)';

                setTimeout(() => {
                    col.remove();
                    const remaining = likedGrid.querySelectorAll('[class*="col-"]').length;
                    likedEmpty.style.display = remaining === 0 ? 'flex' : 'none';
                }, 260);
            }
        } catch (error) {
            console.error(error);
        }
    });
}
