(function () {
	if (!document.getElementById('wikijs_dashboard_settings')) {
		return;
	}

	const el = (id) => document.getElementById(id);
	const btn = el('wikijs_dashboard_save');
	if (!btn) return;

	const show = el('wikijs_token_show');
	const tokenInput = el('wikijs_token');
	if (show && tokenInput) {
		show.addEventListener('change', () => {
			tokenInput.type = show.checked ? 'text' : 'password';
		});
	}


	btn.addEventListener('click', async (e) => {
		e.preventDefault();
		btn.disabled = true;
		try {
			const url = OC.generateUrl('/apps/wikijs_dashboard/settings/admin');
			const body = new URLSearchParams();
			body.set('wikijs_url', el('wikijs_url').value || '');
			body.set('wikijs_token', el('wikijs_token').value || '');
			body.set('wikijs_public_url', el('wikijs_public_url').value || '');
			body.set('wikijs_locale', el('wikijs_locale').value || 'cs');
			body.set('limit', el('limit').value || '7');
			body.set('requesttoken', (OC && OC.requestToken) ? OC.requestToken : '');

			const res = await fetch(url, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
				},
				body: body.toString(),
			});

			if (!res.ok) {
				throw new Error('HTTP ' + res.status);
			}
			const json = await res.json();
			if (!json || json.status !== 'ok') {
				throw new Error('Unexpected response');
			}
			OC.Notification.showTemporary(t('wikijs_dashboard', 'Saved'));
		} catch (err) {
			console.error('wikijs_dashboard settings save failed', err);
			OC.Notification.showTemporary(t('wikijs_dashboard', 'Save failed'));
		} finally {
			btn.disabled = false;
		}
	});
})();
