<?php 
include '../includes/config.php';
include '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$nome, $descricao, $preco, $estoque])) {
            $_SESSION['success'] = "Produto adicionado com sucesso!";
            header("Location: index.php");
            exit();
        } else {
            $error = "Erro ao adicionar produto.";
        }
    } catch (PDOException $e) {
        $error = "Erro no banco de dados: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Adicionar Novo Produto</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome do Produto</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3" required></textarea>
    </div>
    
    <div class="mb-3">
        <label for="preco" class="form-label">Preço (R$)</label>
        <input type="number" step="0.01" class="form-control" id="preco" name="preco" min="0.01" required>
    </div>
    
    <div class="mb-3">
        <label for="estoque" class="form-label">Quantidade em Estoque</label>
        <input type="number" class="form-control" id="estoque" name="estoque" min="0" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Adicionar Produto</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>