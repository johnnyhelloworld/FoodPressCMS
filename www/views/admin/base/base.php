<!DOCTYPE html>
<html lang="fr">

<?php include_once __DIR__ . '/../partials/head.php' ?>
<?php include_once __DIR__ . '/../partials/header.php' ?>

<body id="body-pd">
    <main style="min-height:58vh">
        <?= $content ?>
    </main>

    <?php include_once __DIR__ . '/../partials/footer.php' ?>

    <script src="https://unpkg.com/ionicons@5.1.2/dist/ionicons.js"></script>
    <script src="../../../public/src/js/script.js"></script>
</body>
</html>

<style>
    .nav__link {
        text-decoration: none;
        color: black;
    }

    .active-link {
        border-bottom: 2px solid lightgreen;
    }
</style>