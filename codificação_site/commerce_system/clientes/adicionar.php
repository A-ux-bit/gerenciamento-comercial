<?php 
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    
    $stmt = $pdo->prepare("INSERT INTO clientes (nome, endereco, telefone, email) VALUES (?, ?, ?, ?)");
    if ($stmt->execute([$nome, $endereco, $telefone, $email])) {
        $_SESSION['success'] = "Cliente adicionado com sucesso!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Erro ao adicionar cliente.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Adicionar Cliente</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="endereco" name="endereco" required>
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <button type="submit" class="btn btn-primary">Adicionar</button>
</form>

<?php include '../includes/footer.php'; ?>