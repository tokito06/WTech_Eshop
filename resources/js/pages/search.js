export default function initSearchPage() {
    const searchForm = document.getElementById('search-filters-form');
    const searchEditable = document.getElementById('search-query-editable');
    const searchHidden = document.getElementById('search-query-hidden');
    const searchClear = document.getElementById('search-tag-clear');

    const syncSearchQuery = () => {
        if (!searchEditable || !searchHidden) {
            return;
        }

        searchHidden.value = searchEditable.value.trim();
    };

    if (searchEditable && searchForm) {
        searchEditable.addEventListener('input', syncSearchQuery);
        searchEditable.addEventListener('blur', () => {
            syncSearchQuery();
        });
        searchEditable.addEventListener('keydown', event => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            syncSearchQuery();
            searchForm.requestSubmit();
        });
    }

    if (searchClear && searchEditable && searchForm) {
        searchClear.addEventListener('click', () => {
            searchEditable.value = '';
            syncSearchQuery();
            searchForm.requestSubmit();
        });
    }

    const filtersForm = document.getElementById('filters-body');

    const submitFilters = () => {
        if (!filtersForm) {
            return;
        }

        if (typeof filtersForm.requestSubmit === 'function') {
            filtersForm.requestSubmit();
            return;
        }

        filtersForm.submit();
    };

    document.querySelectorAll('.filter-category').forEach(list => {
        const radios = list.querySelectorAll('input[type="radio"]');

        radios.forEach(radio => {
            radio.addEventListener('change', () => {
                list.querySelectorAll('li').forEach(item => item.classList.remove('active'));
                radio.closest('li')?.classList.add('active');
                submitFilters();
            });
        });
    });

    document.querySelectorAll('.size-btn input[type="checkbox"]').forEach(input => {
        input.addEventListener('change', () => {
            input.closest('.size-btn')?.classList.toggle('active', input.checked);
            submitFilters();
        });
    });

    const filtersToggle = document.getElementById('filters-toggle');
    const filtersBody = document.getElementById('filters-body');
    const filtersIcon = document.getElementById('filters-icon');

    if (filtersToggle && filtersBody && filtersIcon) {
        filtersToggle.addEventListener('click', () => {
            const isOpen = filtersBody.classList.toggle('open');
            filtersIcon.textContent = isOpen ? 'expand_less' : 'expand_more';
        });
    }

    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    if (priceMin && priceMax) {
        const normalizePriceRange = () => {
            if (priceMin.value !== '' && priceMax.value !== '' && +priceMin.value > +priceMax.value) {
                priceMax.value = priceMin.value;
            }
        };

        const submitOnPriceBlur = () => {
            window.setTimeout(() => {
                const activeElement = document.activeElement;

                if (activeElement === priceMin || activeElement === priceMax) {
                    return;
                }

                normalizePriceRange();
                submitFilters();
            }, 0);
        };

        const submitOnEnter = event => {
            if (event.key !== 'Enter') {
                return;
            }

            event.preventDefault();
            normalizePriceRange();
            submitFilters();
        };

        priceMin.addEventListener('change', normalizePriceRange);
        priceMax.addEventListener('change', () => {
            if (priceMin.value !== '' && priceMax.value !== '' && +priceMax.value < +priceMin.value) {
                priceMin.value = priceMax.value;
            }
        });

        priceMin.addEventListener('blur', submitOnPriceBlur);
        priceMax.addEventListener('blur', submitOnPriceBlur);
        priceMin.addEventListener('keydown', submitOnEnter);
        priceMax.addEventListener('keydown', submitOnEnter);
    }
}
