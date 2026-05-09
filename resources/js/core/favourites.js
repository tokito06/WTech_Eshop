export default function initFavouriteToggles() {
    document.addEventListener('click', async event => {
        const toggleBtn = event.target.closest('[data-favourite-toggle]');
        if (!toggleBtn) {
            return;
        }

        const body = document.body;
        const isAuthed = body?.dataset?.auth === '1';
        if (!isAuthed) {
            window.location.href = body?.dataset?.loginUrl || '/login';
            return;
        }

        const productId = toggleBtn.dataset.productId;
        const toggleUrl = body?.dataset?.favouriteUrl;
        if (!productId || !toggleUrl) {
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (!csrfToken) {
            return;
        }

        try {
            const response = await fetch(toggleUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ product_id: productId }),
            });

            const data = await response.json().catch(() => ({}));
            if (!response.ok || !data.success) {
                throw new Error('Unable to update favourites');
            }

            const isFavourited = Boolean(data.favourited);
            toggleBtn.dataset.favourited = isFavourited ? '1' : '0';
            toggleBtn.classList.toggle('liked', isFavourited);

            const icon = toggleBtn.querySelector('.material-symbols-outlined');
            if (icon) {
                icon.textContent = isFavourited ? 'favorite' : 'favorite_border';
                icon.style.fontVariationSettings = isFavourited ? "'FILL' 1" : "'FILL' 0";
            }

            toggleBtn.setAttribute(
                'aria-label',
                isFavourited ? 'Remove from favourites' : 'Add to favourites'
            );
            if (toggleBtn.title !== undefined) {
                toggleBtn.title = isFavourited ? 'Remove from favourites' : 'Add to favourites';
            }
        } catch (error) {
            console.error(error);
        }
    });
}
