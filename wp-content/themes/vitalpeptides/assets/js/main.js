/**
 * Vital Peptides theme scripts.
 * Typing headline, hero parallax, carousel, quality tabs, accordion, ticker,
 * cart drawer (AJAX), qty controls, sticky mobile bar, newsletter, fade-in sections.
 */
(function ($) {
	'use strict';

	/* ------------------------------------------------------------------
	 * Mobile menu + header search
	 * ---------------------------------------------------------------- */
	var mobileToggle = document.querySelector('.vp-mobile-toggle');
	var mobileMenu = document.querySelector('.vp-mobile-menu');
	if (mobileToggle && mobileMenu) {
		mobileToggle.addEventListener('click', function () {
			var open = !mobileMenu.hidden;
			mobileMenu.hidden = open;
			mobileToggle.setAttribute('aria-expanded', String(!open));
		});
	}

	var searchToggle = document.querySelector('.vp-search-toggle');
	var headerSearch = document.querySelector('.vp-header-search');
	if (searchToggle && headerSearch) {
		searchToggle.addEventListener('click', function () {
			headerSearch.hidden = !headerSearch.hidden;
			if (!headerSearch.hidden) {
				var input = headerSearch.querySelector('input[type="search"]');
				if (input) { input.focus(); }
			}
		});
		var searchClose = headerSearch.querySelector('.vp-search-close');
		if (searchClose) {
			searchClose.addEventListener('click', function () { headerSearch.hidden = true; });
		}
	}

	/* ------------------------------------------------------------------
	 * Typing headline (hero)
	 * ---------------------------------------------------------------- */
	var typedEl = document.querySelector('.vp-typed-text');
	if (typedEl) {
		var words = [];
		try { words = JSON.parse(typedEl.getAttribute('data-words')) || []; } catch (e) { words = []; }
		if (words.length) {
			var wordIndex = 0, displayed = '', deleting = false;
			var TYPE = 90, DEL = 50, PAUSE = 2200;
			var tick = function () {
				var current = words[wordIndex];
				var delay;
				if (!deleting && displayed === current) {
					deleting = true;
					delay = PAUSE;
				} else if (deleting && displayed === '') {
					deleting = false;
					wordIndex = (wordIndex + 1) % words.length;
					delay = TYPE;
				} else {
					displayed = deleting ? current.slice(0, displayed.length - 1) : current.slice(0, displayed.length + 1);
					typedEl.textContent = displayed;
					delay = deleting ? DEL : TYPE;
				}
				setTimeout(tick, delay);
			};
			tick();
		}
	}

	/* ------------------------------------------------------------------
	 * Hero molecular glow parallax
	 * ---------------------------------------------------------------- */
	var glow = document.querySelector('.vp-hero-glow');
	if (glow) {
		window.addEventListener('scroll', function () {
			glow.style.transform = 'translateX(-10%) translateY(' + (window.scrollY * 0.2) + 'px)';
		}, { passive: true });
	}

	/* ------------------------------------------------------------------
	 * Fade-in on scroll
	 * ---------------------------------------------------------------- */
	var fadeEls = document.querySelectorAll('.fade-in-section');
	if (fadeEls.length && 'IntersectionObserver' in window) {
		var fadeObserver = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('visible');
					fadeObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.1 });
		fadeEls.forEach(function (el) { fadeObserver.observe(el); });
	} else {
		fadeEls.forEach(function (el) { el.classList.add('visible'); });
	}

	/* ------------------------------------------------------------------
	 * Featured carousel (scroll-snap-free translate carousel, looping + autoplay)
	 * ---------------------------------------------------------------- */
	document.querySelectorAll('.vp-carousel').forEach(function (carousel) {
		var track = carousel.querySelector('.vp-carousel-track');
		var items = carousel.querySelectorAll('.vp-carousel-item');
		var wrap = carousel.closest('.vp-carousel-wrap');
		var prevBtn = wrap ? wrap.querySelector('.vp-carousel-prev') : null;
		var nextBtn = wrap ? wrap.querySelector('.vp-carousel-next') : null;
		if (!track || !items.length) { return; }

		var index = 0;
		var autoplayDelay = parseInt(carousel.getAttribute('data-autoplay'), 10) || 0;
		var autoplayTimer = null;

		function perView() {
			var w = window.innerWidth;
			if (w >= 1280) { return 4; }
			if (w >= 1024) { return 3; }
			if (w >= 768) { return 2; }
			return 1;
		}
		function maxIndex() { return Math.max(0, items.length - perView()); }
		function update() {
			var itemWidth = items[0].getBoundingClientRect().width;
			track.style.transform = 'translateX(' + (-index * itemWidth) + 'px)';
			if (prevBtn) { prevBtn.disabled = index <= 0; }
			if (nextBtn) { nextBtn.disabled = index >= maxIndex(); }
		}
		function go(dir, fromAutoplay) {
			index += dir;
			if (fromAutoplay) {
				if (index > maxIndex()) { index = 0; }
			}
			index = Math.max(0, Math.min(index, maxIndex()));
			update();
		}
		if (prevBtn) { prevBtn.addEventListener('click', function () { go(-1); stopAutoplay(); }); }
		if (nextBtn) { nextBtn.addEventListener('click', function () { go(1); stopAutoplay(); }); }

		function startAutoplay() {
			if (!autoplayDelay) { return; }
			autoplayTimer = setInterval(function () { go(1, true); }, autoplayDelay);
		}
		function stopAutoplay() {
			if (autoplayTimer) { clearInterval(autoplayTimer); autoplayTimer = null; }
		}
		startAutoplay();

		// Touch swipe
		var startX = 0, isSwiping = false;
		carousel.addEventListener('touchstart', function (e) {
			startX = e.touches[0].clientX; isSwiping = true;
		}, { passive: true });
		carousel.addEventListener('touchend', function (e) {
			if (!isSwiping) { return; }
			var dx = e.changedTouches[0].clientX - startX;
			if (Math.abs(dx) > 40) { go(dx < 0 ? 1 : -1); stopAutoplay(); }
			isSwiping = false;
		}, { passive: true });

		window.addEventListener('resize', update);
		update();
	});

	/* ------------------------------------------------------------------
	 * Quality tabs
	 * ---------------------------------------------------------------- */
	document.querySelectorAll('.vp-quality-tab').forEach(function (tab) {
		tab.addEventListener('click', function () {
			var id = tab.getAttribute('data-tab');
			document.querySelectorAll('.vp-quality-tab').forEach(function (t) {
				t.classList.toggle('is-active', t === tab);
				t.setAttribute('aria-selected', t === tab ? 'true' : 'false');
			});
			document.querySelectorAll('.vp-quality-panel').forEach(function (p) {
				p.classList.toggle('is-active', p.getAttribute('data-panel') === id);
			});
		});
	});

	/* ------------------------------------------------------------------
	 * Accordions (FAQ)
	 * ---------------------------------------------------------------- */
	document.querySelectorAll('.vp-accordion-trigger').forEach(function (trigger) {
		trigger.addEventListener('click', function () {
			var item = trigger.closest('.vp-accordion-item');
			var content = item.querySelector('.vp-accordion-content');
			var isOpen = item.classList.contains('is-open');

			// Close siblings (single-open accordion)
			var accordion = item.closest('.vp-accordion');
			accordion.querySelectorAll('.vp-accordion-item.is-open').forEach(function (open) {
				open.classList.remove('is-open');
				open.querySelector('.vp-accordion-content').hidden = true;
				open.querySelector('.vp-accordion-trigger').setAttribute('aria-expanded', 'false');
			});

			if (!isOpen) {
				item.classList.add('is-open');
				content.hidden = false;
				trigger.setAttribute('aria-expanded', 'true');
			}
		});
	});

	/* ------------------------------------------------------------------
	 * Shop sort dropdown
	 * ---------------------------------------------------------------- */
	var sortToggle = document.querySelector('.vp-sort-toggle');
	var sortMenu = document.querySelector('.vp-sort-menu');
	if (sortToggle && sortMenu) {
		sortToggle.addEventListener('click', function (e) {
			e.stopPropagation();
			var open = !sortMenu.hidden;
			sortMenu.hidden = open;
			sortToggle.setAttribute('aria-expanded', String(!open));
		});
		document.addEventListener('click', function () {
			sortMenu.hidden = true;
			sortToggle.setAttribute('aria-expanded', 'false');
		});
	}

	/* ------------------------------------------------------------------
	 * Cart drawer open/close
	 * ---------------------------------------------------------------- */
	var drawer = document.querySelector('.vp-cart-drawer');
	var overlay = document.querySelector('.vp-drawer-overlay');

	function openDrawer() {
		if (!drawer || !overlay) { return; }
		drawer.hidden = false;
		overlay.hidden = false;
		document.body.classList.add('vp-drawer-open');
		requestAnimationFrame(function () {
			requestAnimationFrame(function () {
				drawer.classList.add('is-visible');
				overlay.classList.add('is-visible');
			});
		});
	}
	function closeDrawer() {
		if (!drawer || !overlay) { return; }
		drawer.classList.remove('is-visible');
		overlay.classList.remove('is-visible');
		document.body.classList.remove('vp-drawer-open');
		setTimeout(function () {
			drawer.hidden = true;
			overlay.hidden = true;
		}, 350);
	}
	document.querySelectorAll('.vp-cart-toggle').forEach(function (btn) {
		btn.addEventListener('click', openDrawer);
	});
	if (overlay) { overlay.addEventListener('click', closeDrawer); }
	document.addEventListener('click', function (e) {
		if (e.target.closest('.vp-drawer-close')) { closeDrawer(); }
	});
	document.addEventListener('keydown', function (e) {
		if (e.key === 'Escape') { closeDrawer(); }
	});

	/* ------------------------------------------------------------------
	 * Toast
	 * ---------------------------------------------------------------- */
	function toast(message) {
		var el = document.querySelector('.vp-toast');
		if (!el) {
			el = document.createElement('div');
			el.className = 'vp-toast';
			document.body.appendChild(el);
		}
		el.textContent = message;
		el.classList.add('is-visible');
		clearTimeout(el._t);
		el._t = setTimeout(function () { el.classList.remove('is-visible'); }, 2500);
	}

	/* ------------------------------------------------------------------
	 * AJAX add to cart (product cards, single product, drawer suggestions)
	 * ---------------------------------------------------------------- */
	function refreshDrawerCount() {
		var body = document.getElementById('vp-drawer-body');
		var countEl = document.querySelector('.vp-drawer-count');
		if (body && countEl) {
			var qty = 0;
			body.querySelectorAll('.vp-drawer-qty').forEach(function (q) { qty += parseInt(q.textContent, 10) || 0; });
			countEl.textContent = qty;
		}
	}

	document.addEventListener('click', function (e) {
		var btn = e.target.closest('.vp-add-to-cart');
		if (!btn || typeof vpData === 'undefined' || !vpData.wcAjaxUrl) { return; }
		e.preventDefault();

		var productId = btn.getAttribute('data-product-id');
		var qty = 1;
		var buyRow = btn.closest('.vp-product-buy, .vp-sticky-bar');
		if (buyRow) {
			var qtyEl = buyRow.querySelector('.vp-qty-value');
			if (qtyEl) { qty = parseInt(qtyEl.textContent, 10) || 1; }
		}

		btn.classList.add('is-loading');
		var data = new FormData();
		data.append('product_id', productId);
		data.append('quantity', qty);

		fetch(vpData.wcAjaxUrl.replace('%%endpoint%%', 'add_to_cart'), {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})
			.then(function (r) { return r.json(); })
			.then(function (res) {
				btn.classList.remove('is-loading');
				if (res && res.fragments) {
					applyFragments(res.fragments);
					openDrawer();
					refreshDrawerCount();
				} else if (res && res.error && res.product_url) {
					window.location = res.product_url;
				}
			})
			.catch(function () { btn.classList.remove('is-loading'); toast('Something went wrong. Please try again.'); });
	});

	function applyFragments(fragments) {
		Object.keys(fragments).forEach(function (selector) {
			document.querySelectorAll(selector).forEach(function (el) {
				var tmp = document.createElement('div');
				tmp.innerHTML = fragments[selector];
				var replacement = tmp.firstElementChild;
				if (replacement) { el.replaceWith(replacement); }
			});
		});
	}

	/* ------------------------------------------------------------------
	 * Drawer qty +/- and remove (event delegation, works after fragment swaps)
	 * ---------------------------------------------------------------- */
	function updateCartItem(cartKey, quantity) {
		if (typeof vpData === 'undefined') { return; }
		var data = new FormData();
		data.append('action', 'vp_update_cart_item');
		data.append('nonce', vpData.nonce);
		data.append('cart_key', cartKey);
		data.append('quantity', quantity);

		fetch(vpData.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: data })
			.then(function (r) { return r.json(); })
			.then(function (res) {
				if (res && res.fragments) {
					applyFragments(res.fragments);
					refreshDrawerCount();
				}
			});
	}

	document.addEventListener('click', function (e) {
		var item = e.target.closest('.vp-drawer-item');
		if (!item) { return; }
		var key = item.getAttribute('data-cart-key');
		var qtyEl = item.querySelector('.vp-drawer-qty');
		var qty = qtyEl ? parseInt(qtyEl.textContent, 10) || 1 : 1;

		if (e.target.closest('.vp-drawer-plus')) { updateCartItem(key, qty + 1); }
		else if (e.target.closest('.vp-drawer-minus')) { updateCartItem(key, qty - 1); }
		else if (e.target.closest('.vp-drawer-remove')) { updateCartItem(key, 0); }
	});

	/* ------------------------------------------------------------------
	 * Single product qty + dynamic price + sticky bar
	 * ---------------------------------------------------------------- */
	var buyRow = document.getElementById('vp-buy-row');
	if (buyRow) {
		var price = parseFloat(buyRow.getAttribute('data-price')) || 0;
		var currency = buyRow.getAttribute('data-currency') || '$';

		function formatTotal(qty) {
			return currency + (price * qty).toFixed(2);
		}
		function syncQty(qty) {
			document.querySelectorAll('.vp-qty-value').forEach(function (el) { el.textContent = qty; });
			document.querySelectorAll('.vp-buy-total').forEach(function (el) { el.textContent = formatTotal(qty); });
		}
		document.addEventListener('click', function (e) {
			var plus = e.target.closest('.vp-qty-plus');
			var minus = e.target.closest('.vp-qty-minus');
			if (!plus && !minus) { return; }
			var box = e.target.closest('.vp-qty-box');
			if (!box || box.closest('.vp-drawer-item')) { return; }
			var el = box.querySelector('.vp-qty-value');
			var qty = parseInt(el.textContent, 10) || 1;
			qty = plus ? qty + 1 : Math.max(1, qty - 1);
			syncQty(qty);
		});

		// Sticky mobile bar visibility (shows when buy row scrolled out)
		var stickyBar = document.querySelector('.vp-sticky-bar');
		if (stickyBar && 'IntersectionObserver' in window && window.innerWidth < 768) {
			new IntersectionObserver(function (entries) {
				stickyBar.hidden = entries[0].isIntersecting;
			}, { threshold: 0 }).observe(buyRow);
		}
	}

	/* ------------------------------------------------------------------
	 * Newsletter (homepage CTA)
	 * ---------------------------------------------------------------- */
	var newsletterForm = document.querySelector('.vp-newsletter-form');
	if (newsletterForm) {
		newsletterForm.addEventListener('submit', function (e) {
			e.preventDefault();
			if (typeof vpData === 'undefined') { return; }
			var input = newsletterForm.querySelector('input[type="email"]');
			var btn = newsletterForm.querySelector('button[type="submit"]');
			var msg = newsletterForm.querySelector('.vp-newsletter-msg');
			var email = (input.value || '').trim();

			if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
				msg.textContent = 'Please enter a valid email address';
				msg.classList.add('is-error');
				msg.hidden = false;
				return;
			}
			btn.disabled = true;
			btn.textContent = '...';

			var data = new FormData();
			data.append('action', 'vp_newsletter');
			data.append('nonce', vpData.nonce);
			data.append('email', email);

			fetch(vpData.ajaxUrl, { method: 'POST', credentials: 'same-origin', body: data })
				.then(function (r) { return r.json(); })
				.then(function (res) {
					btn.disabled = false;
					btn.textContent = 'Subscribe';
					msg.hidden = false;
					if (res && res.success) {
						msg.textContent = res.data.message;
						msg.classList.remove('is-error');
						input.value = '';
					} else {
						msg.textContent = (res && res.data && res.data.message) || 'Something went wrong. Please try again.';
						msg.classList.add('is-error');
					}
				})
				.catch(function () {
					btn.disabled = false;
					btn.textContent = 'Subscribe';
					msg.textContent = 'Something went wrong. Please try again.';
					msg.classList.add('is-error');
					msg.hidden = false;
				});
		});
	}
})(window.jQuery);
