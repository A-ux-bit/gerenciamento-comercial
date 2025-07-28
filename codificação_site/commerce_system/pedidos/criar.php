<?php 
include '../includes/config.php';
include '../includes/functions.php';

$clientes = getClientes();
$produtos = getProdutos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cliente_id = $_POST['cliente_id'];
    $produtos_ids = $_POST['produto_id'];
    $quantidades = $_POST['quantidade'];
    
    try {
        $pdo->beginTransaction();
        
        // Inserir pedido
        $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_id, data, total) VALUES (?, CURDATE(), 0)");
        $stmt->execute([$cliente_id]);
        $pedido_id = $pdo->lastInsertId();
        
        $total = 0;
        
        // Inserir itens do pedido e calcular total
        for ($i = 0; $i < count($produtos_ids); $i++) {
            $produto_id = $produtos_ids[$i];
            $quantidade = $quantidades[$i];
            
            if ($quantidade <= 0) continue;
            
            // Obter preço do produto
            $produto = getProdutoById($produto_id);
            $preco_unitario = $produto['preco'];
            $subtotal = $preco_unitario * $quantidade;
            $total += $subtotal;
            
            // Inserir item do pedido
            $stmt = $pdo->prepare("INSERT INTO itens_pedido (pedido_id, produto_id, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
            $stmt->execute([$pedido_id, $produto_id, $quantidade, $preco_unitario]);
            
            // Atualizar estoque
            $stmt = $pdo->prepare("UPDATE produtos SET estoque = estoque - ? WHERE id = ?");
            $stmt->execute([$quantidade, $produto_id]);
        }
        
        // Atualizar total do pedido
        $stmt = $pdo->prepare("UPDATE pedidos SET total = ? WHERE id = ?");
        $stmt->execute([$total, $pedido_id]);
        
        $pdo->commit();
        
        $_SESSION['success'] = "Pedido criado com sucesso! Total: R$ " . number_format($total, 2, ',', '.');
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Erro ao criar pedido: " . $e->getMessage();
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Criar Novo Pedido</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label for="cliente_id" class="form-label">Cliente</label>
        <select class="form-select" id="cliente_id" name="cliente_id" required>
            <option value="">Selecione um cliente</option>
            <?php foreach ($clientes as $cliente): ?>
            <option value="<?php echo $cliente['id']; ?>"><?php echo htmlspecialchars($cliente['nome']); ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <h4>Itens do Pedido</h4>
    <div id="itens-container">
        <div class="item-pedido row mb-3">
            <div class="col-md-6">
                <label class="form-label">Produto</label>
                <select class="form-select produto-select" name="produto_id[]" required>
                    <option value="">Selecione um produto</option>
                    <?php foreach ($produtos as $produto): ?>
                    <option value="<?php echo $produto['id']; ?>" data-preco="<?php echo $produto['preco']; ?>" data-estoque="<?php echo $produto['estoque']; ?>">
                        <?php echo htmlspecialchars($produto['nome']); ?> (R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?>)
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Quantidade</label>
                <input type="number" class="form-control quantidade" name="quantidade[]" min="1" required>
                <small class="text-muted estoque-info"></small>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" class="btn btn-danger remover-item">Remover</button>
            </div>
        </div>
    </div>
    
    <button type="button" id="adicionar-item" class="btn btn-secondary mb-3">Adicionar Item</button>
    
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Criar Pedido</button>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Adicionar novo item
    document.getElementById('adicionar-item').addEventListener('click', function() {
        const container = document.getElementById('itens-container');
        const novoItem = container.firstElementChild.cloneNode(true);
        
        // Limpar seleções e valores
        novoItem.querySelector('.produto-select').selectedIndex = 0;
        novoItem.querySelector('.quantidade').value = '';
        novoItem.querySelector('.estoque-info').textContent = '';
        
        container.appendChild(novoItem);
        atualizarEventos();
    });
    
    // Remover item
    function atualizarEventos() {
        document.querySelectorAll('.remover-item').forEach(btn => {
            btn.addEventListener('click', function() {
                if (document.querySelectorAll('.item-pedido').length > 1) {
                    this.closest('.item-pedido').remove();
                } else {
                    alert('O pedido deve ter pelo menos um item.');
                }
            });
        });
        
        // Atualizar informações de estoque quando selecionar produto
        document.querySelectorAll('.produto-select').forEach(select => {
            select.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const estoque = selectedOption.dataset.estoque || 0;
                const preco = selectedOption.dataset.preco || 0;
                
                const quantidadeInput = this.closest('.item-pedido').querySelector('.quantidade');
                quantidadeInput.max = estoque;
                
                const estoqueInfo = this.closest('.item-pedido').querySelector('.estoque-info');
                estoqueInfo.textContent = `Estoque: ${estoque}`;
                
                if (estoque <= 0) {
                    estoqueInfo.classList.add('text-danger');
                    estoqueInfo.textContent += ' (Sem estoque)';
                } else {
                    estoqueInfo.classList.remove('text-danger');
                }
            });
        });
    }
    
    atualizarEventos();
});
</script>

<?php include '../includes/footer.php'; ?>