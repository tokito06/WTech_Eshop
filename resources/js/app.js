import './bootstrap';
import initFavouriteToggles from './core/favourites';
import initAdminAddProductPage from './pages/admin-add-product';
import initAdminEditProductPage from './pages/admin-edit-product';
import initCartPage from './pages/cart';
import initDeliveryPage from './pages/delivery';
import initFavouritesPage from './pages/favourites';
import initIndexPage from './pages/index';
import initPaymentPage from './pages/payment';
import initProductPage from './pages/product';
import initProfilePage from './pages/profile';
import initRegisterPage from './pages/register';
import initSearchPage from './pages/search';

const boot = () => {
	initFavouriteToggles();
	initIndexPage();
	initCartPage();
	initProductPage();
	initSearchPage();
	initFavouritesPage();
	initDeliveryPage();
	initPaymentPage();
	initProfilePage();
	initRegisterPage();
	initAdminAddProductPage();
	initAdminEditProductPage();
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', boot);
} else {
	boot();
}

