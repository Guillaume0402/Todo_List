<?php
// Partiel footer (copie du contenu de templates/footer.php)
?>
</div>
</div> <!-- Fermeture du content-wrapper -->
<footer class="py-2 mt-auto">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <span class="text-primary fw-semibold">TyckyList</span>
                <span class="text-body-secondary ms-2">- Organisez vos tâches facilement</span>
            </div>
            <div class="col-md-6 text-md-end">
                <nav class="d-inline-flex">
                    <a href="<?= AppConfig::BASE_PATH ?>?r=home/index" class="nav-link px-2 py-1">Accueil</a>
                    <a href="<?= AppConfig::BASE_PATH ?>?r=lists/index" class="nav-link px-2 py-1">Mes listes</a>
                    <a href="<?= AppConfig::BASE_PATH ?>?r=home/about" class="nav-link px-2 py-1">À propos</a>
                </nav>
            </div>
        </div>
        <hr class="my-2 border-primary opacity-25">
        <div class="text-center">
            <small class="text-body-secondary">
                © 2025 TyckyList. Tous droits réservés.
            </small>
        </div>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
</script>
<script>
    (function() {
        const selector = '[data-toggle-status="1"]';

        const buildUrlWithStatus = (href, status) => {
            const targetUrl = new URL(href, window.location.origin);
            targetUrl.searchParams.delete('ajax');
            targetUrl.searchParams.set('status', status ? 1 : 0);
            return targetUrl.pathname + targetUrl.search;
        };

        document.addEventListener('click', async (event) => {
            const toggleLink = event.target.closest(selector);
            if (!toggleLink) {
                return;
            }

            event.preventDefault();

            if (toggleLink.dataset.loading === '1') {
                return;
            }

            const currentStatus = toggleLink.dataset.currentStatus === '1';
            const nextStatus = currentStatus ? 0 : 1;
            const requestUrl = new URL(toggleLink.getAttribute('href'), window.location.origin);
            requestUrl.searchParams.set('status', nextStatus);
            requestUrl.searchParams.set('ajax', '1');

            toggleLink.dataset.loading = '1';

            try {
                const response = await fetch(requestUrl.pathname + requestUrl.search, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error('Requête invalide');
                }

                const payload = await response.json();

                if (!payload || !payload.success) {
                    throw new Error(payload && payload.message ? payload.message : 'Mise à jour impossible');
                }

                const effectiveStatus = payload.status === 1 || payload.status === true;
                toggleLink.dataset.currentStatus = effectiveStatus ? '1' : '0';
                toggleLink.setAttribute('href', buildUrlWithStatus(toggleLink.getAttribute('href'), effectiveStatus ? 0 : 1));

                const icon = toggleLink.querySelector('i');
                if (icon) {
                    icon.classList.toggle('bi-check-circle-fill', effectiveStatus);
                    icon.classList.toggle('bi-check-circle', !effectiveStatus);
                }
            } catch (error) {
                console.error(error);
                alert(error.message || "Une erreur est survenue lors de la mise à jour de l'item");
            } finally {
                delete toggleLink.dataset.loading;
            }
        });
    })();
</script>
<script>
    document.addEventListener('click', e => {
        const btn = e.target.closest('.toggle-password');
        if (!btn) return;
        const input = btn.closest('.input-group').querySelector('input');
        if (!input) return;

        const isVisible = input.type === 'text';
        input.type = isVisible ? 'password' : 'text';
        btn.textContent = isVisible ? '👁' : '🙈';
    });
</script>

</body>

</html>