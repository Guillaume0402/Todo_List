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
</body>

</html>