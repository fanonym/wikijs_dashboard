(function () {
  const WIDGET_ID = 'wikijs_dashboard_widget';

  function formatDate(iso) {
    if (!iso) return '';
    const d = new Date(iso);
    if (Number.isNaN(d.getTime())) return iso;
    return d.toLocaleString();
  }

  function render(el, items) {
    el.innerHTML = '';
    const ul = document.createElement('ul');
    ul.style.listStyle = 'none';
    ul.style.padding = '0';
    ul.style.margin = '0';

    if (!items || items.length === 0) {
      const li = document.createElement('li');
      li.textContent = 'Žádné položky.';
      ul.appendChild(li);
      el.appendChild(ul);
      return;
    }

    items.forEach((it) => {
      const li = document.createElement('li');
      li.style.marginBottom = '8px';

      const a = document.createElement('a');
      a.href = it.url;
      a.textContent = it.title || it.url;
      a.style.fontWeight = "bold";
      a.target = '_blank';
      a.rel = 'noopener noreferrer';

      const small = document.createElement('div');
      small.style.opacity = '0.7';
      small.style.fontSize = '12px';
      small.textContent = formatDate(it.updatedAt);

      li.appendChild(a);
      li.appendChild(small);
      ul.appendChild(li);
    });

    el.appendChild(ul);
  }

  function loadInto(el) {
    const content = document.createElement('div');
    content.className = 'wikijs-dashboard-content';
    content.style.padding = '12px';
    content.textContent = 'Načítám…';
    el.innerHTML = '';
    el.appendChild(content);

    // API URL from PHP initial state (works with apps_paths URL prefixes like /apps-external)
    const apiUrl = OC.generateUrl('/apps/wikijs_dashboard/api/changes');
fetch(apiUrl, {
      method: 'GET',
      headers: { 'Accept': 'application/json', 'requesttoken': OC.requestToken },
      credentials: 'same-origin',
    })
      .then(async (r) => {
        const text = await r.text();
        let data = null;
        try { data = JSON.parse(text); } catch (e) {}
        return { status: r.status, data, text };
      })
      .then(({ status, data, text }) => {
        if (!data || !data.ok) {
          // Show HTTP status and small body snippet when response isn't JSON (e.g. proxy/firewall)
          if (!data) {
            content.textContent = `HTTP ${status}: ${text.slice(0, 200)}`;
            return;
          }
          const msg = (data && data.error) ? data.error : 'Chyba načítání';
          content.textContent = msg;
          return;
        }
        render(content, data.items);
      })
      .catch((err) => {
        content.textContent = String(err);
      });
  }

  document.addEventListener('DOMContentLoaded', () => {
    if (!window.OCA || !OCA.Dashboard || typeof OCA.Dashboard.register !== 'function') {
      return;
    }
    OCA.Dashboard.register(WIDGET_ID, (el) => loadInto(el));
  });
})();