<?php 
include '../includes/config.php';
include '../includes/functions.php';

$produtos = getProdutos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_id = $_POST['produto_id'];
    $quantidade = $_POST['quantidade'];
    $preco_unitario = $_POST['preco_unitario'];
    $preco_total = $quantidade * $preco_unitario;
    
    try {
        $pdo->beginTransaction();
        
        // Registrar a compra
        $stmt = $pdo->prepare("INSERT INTO compras (produto_id, quantidade, data, preco_total) VALUES (?, ?, CURDATE(), ?)");
        $stmt->execute([$produto_id, $quantidade, $preco_total]);
        
        // Atualizar o estoque
        $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque + ? WHERE id = ?");
        $stmt->execute([$quantidade, $produto_id]);
        
        $pdo->commit();
        
        $_SESSION['success'] = "Compra registrada com sucesso!";
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erro ao registrar compra: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Registrar Nova Compra</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="produto_id" class="form-label">Produto</label>
        <select class="form-select" id="produto_id" name="produto_id" required>
            <option value="">Selecione um produto</option>
            <?php foreach ($produtos as $produto): ?>
            <option value="<?php echo $produto['id']; ?>" data-preco="<?php echo $produto['preco']; ?>">
                <?php echo htmlspecialchars($produto['nome']); ?> (Estoque: <?php echo $produto['estoque']; ?>)
            </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" class="form-control" id="quantidade" name="quantidade" min="1" required>
    </div>
    
    <div class="mb-3">
        <label for="preco_unitario" class="form-label">Preço Unitário (R$)</label>
        <input type="number" step="0.01" class="form-control" id="preco_unitario" name="preco_unitario" required>
    </div>
    
    <div class="mb-3">
        <label class="form-label">Total: R$ <span id="total">0.00</span></label>
    </div>
    
    <button type="submit" class="btn btn-primary">Registrar Compra</button>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produtoSelect = document.getElementById('produto_id');
    const quantidadeInput = document.getElementById('quantidade');
    const precoUnitarioInput = document.getElementById('preco_unitario');
    const totalSpan = document.getElementById('total');
    
    // Atualizar preço unitário quando selecionar produto
    produtoSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            precoUnitarioInput.value = selectedOption.dataset.preco;
            calcularTotal();
        }
    });
    
    // Calcular total quando quantidade ou preço mudar
    quantidadeInput.addEventListener('input', calcularTotal);
    precoUnitarioInput.addEventListener('input', calcularTotal);
    
    function calcularTotal() {
        const quantidade = parseFloat(quantidadeInput.value) || 0;
        const precoUnitario = parseFloat(precoUnitarioInput.value) || 0;
        const total = quantidade * precoUnitario;
        totalSpan.textContent = total.toFixed(2);
    }
});
</script>

<?php include '../includes/footer.php'; ?>