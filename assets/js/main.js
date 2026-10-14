(function () {
	'use strict';

	const header = document.querySelector('.site-header');
	const announcement = document.querySelector('.announcement');
	const toggle = document.querySelector('.menu-toggle');
	const nav = document.querySelector('.site-nav');

	function sticky() {
		if (header) {
			header.classList.toggle('is-sticky', window.scrollY > (announcement ? announcement.offsetHeight : 0) + 24);
		}
	}

	sticky();
	window.addEventListener('scroll', sticky, { passive: true });

	if (toggle && nav) {
		const labels = window.forgetfitTheme || { menuOpen: 'Open menu', menuClose: 'Close menu' };

		function menu(open) {
			toggle.setAttribute('aria-expanded', String(open));
			toggle.setAttribute('aria-label', open ? labels.menuClose : labels.menuOpen);
			nav.classList.toggle('is-open', open);
			document.body.classList.toggle('nav-open', open);
		}

		menu(false);
		toggle.addEventListener('click', () => menu(toggle.getAttribute('aria-expanded') !== 'true'));
		nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => menu(false)));
		document.addEventListener('keydown', (event) => {
			if (event.key === 'Escape' && nav.classList.contains('is-open')) {
				menu(false);
				toggle.focus();
			}
		});
		window.addEventListener('resize', () => {
			if (window.innerWidth > 1080 && nav.classList.contains('is-open')) {
				menu(false);
			}
		}, { passive: true });
	}

	document.querySelectorAll('[data-trial-form]').forEach((form) => {
		const status = form.querySelector('.form-status');
		const button = form.querySelector('.trial-form__submit');
		const started = form.querySelector('[data-trial-started]');
		const settings = window.forgetfitTheme || {};

		if (!status || !button || !window.fetch || !window.FormData || !settings.ajaxUrl) {
			return;
		}

		function showStatus(message, type) {
			status.textContent = message;
			status.hidden = false;
			status.classList.toggle('is-success', type === 'success');
			status.classList.toggle('is-error', type === 'error');
			status.focus();
		}

		form.addEventListener('submit', async (event) => {
			if (!form.checkValidity()) {
				return;
			}

			event.preventDefault();
			button.disabled = true;
			form.classList.add('is-submitting');
			status.hidden = true;

			const data = new FormData(form);
			data.set('action', 'forgetfit_trial_ajax');

			try {
				const response = await fetch(settings.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data,
				});
				const payload = await response.json();

				if (!response.ok || !payload.success) {
					const message = payload && payload.data && payload.data.message
						? payload.data.message
						: settings.networkError;
					showStatus(message, 'error');
					if (payload && payload.data && payload.data.field) {
						const field = form.elements.namedItem(payload.data.field);
						if (field && typeof field.focus === 'function') {
							field.focus();
						}
					}
					return;
				}

				showStatus(payload.data.message, 'success');
				form.reset();
				if (started) {
					started.value = String(Math.floor(Date.now() / 1000));
				}
			} catch (error) {
				showStatus(settings.networkError || 'We could not send your request. Try again.', 'error');
			} finally {
				button.disabled = false;
				form.classList.remove('is-submitting');
			}
		});
	});
}());
