<?php 
include '../includes/config.php';
include '../includes/functions.php';

// Verifica se o ID do pedido foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$pedido_id = intval($_GET['id']);

// Obtém os dados do pedido
$pedido = getPedidoById($pedido_id);
if (!$pedido) {
    $_SESSION['error'] = "Pedido não encontrado.";
    header("Location: index.php");
    exit();
}

// Obtém os itens do pedido
$itens = getItensPedido($pedido_id);

// Obtém informações do cliente
$cliente = getClienteById($pedido['cliente_id']);

// Calcula o total (redundante, mas para garantir)
$total_pedido = 0;
foreach ($itens as $item) {
    $total_pedido += $item['quantidade'] * $item['preco_unitario'];
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Detalhes do Pedido #<?php echo $pedido['id']; ?></h2>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informações do Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Número:</div>
                        <div class="col-sm-8">#<?php echo $pedido['id']; ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Data:</div>
                        <div class="col-sm-8"><?php echo date('d/m/Y H:i', strtotime($pedido['data'])); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Status:</div>
                        <div class="col-sm-8">
                            <span class="badge bg-success">Concluído</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-bold">Total:</div>
                        <div class="col-sm-8 fw-bold">R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informações do Cliente</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Nome:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($cliente['nome']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Endereço:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($cliente['endereco']); ?></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-sm-4 fw-bold">Telefone:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($cliente['telefone']); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 fw-bold">Email:</div>
                        <div class="col-sm-8"><?php echo htmlspecialchars($cliente['email']); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Itens do Pedido</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th class="text-end">Preço Unitário</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $item): ?>
                        <?php $produto = getProdutoById($item['produto_id']); ?>
                        <tr>
                            <td>
                                <?php echo htmlspecialchars($produto['nome']); ?>
                                <small class="text-muted d-block"><?php echo htmlspecialchars($produto['descricao']); ?></small>
                            </td>
                            <td class="text-end">R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                            <td class="text-center"><?php echo $item['quantidade']; ?></td>
                            <td class="text-end">R$ <?php echo number_format($item['quantidade'] * $item['preco_unitario'], 2, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">Total:</th>
                            <th class="text-end">R$ <?php echo number_format($total_pedido, 2, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fas fa-print"></i> Imprimir Pedido
        </button>
        <a href="criar.php?cliente_id=<?php echo $pedido['cliente_id']; ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Novo Pedido para este Cliente
        </a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>