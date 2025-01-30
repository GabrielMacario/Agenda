<?php
    include_once("templates/header.php");
?>

    <div class="container">
        <div id="back-btn-div"><button id="back-btn"><a href="index.php">Voltar</a></button></div>
          <h1 id="main-title">Criar contato</h1>
            <form action="<?= $BASE_URL ?>config/process.php" method="POST"></form>
                <input type="hidden" name="type" valor="create">
    </div>

<?php
    include_once("templates/footer.php");
?>
