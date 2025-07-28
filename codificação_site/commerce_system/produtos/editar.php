<?php 
include '../includes/config.php';
include '../includes/functions.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$produto_id = $_GET['id'];
$produto = getProdutoById($produto_id);

if (!$produto) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $estoque = $_POST['estoque'];
    
    $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ? WHERE id = ?");
    if ($stmt->execute([$nome, $descricao, $preco, $estoque, $produto_id])) {
        $_SESSION['success'] = "Produto atualizado com sucesso!";
        header("Location: index.php");
        exit();
    } else {
        $error = "Erro ao atualizar produto.";
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Editar Produto</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
    </div>
    <div class="mb-3">
        <label for="descricao" class="form-label">Descrição</label>
        <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
    </div>
    <div class="mb-3">
        <label for="preco" class="form-label">Preço (R$)</label>
        <input type="number" step="0.01" class="form-control" id="preco" name="preco" value="<?php echo $produto['preco']; ?>" required>
    </div>
    <div class="mb-3">
        <label for="estoque" class="form-label">Estoque</label>
        <input type="number" class="form-control" id="estoque" name="estoque" value="<?php echo $produto['estoque']; ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>