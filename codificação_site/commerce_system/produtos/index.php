<?php 
include '../includes/config.php';
include '../includes/functions.php';

$produtos = getProdutos();
?>

<?php include '../includes/header.php'; ?>

<h2>Produtos</h2>
<a href="adicionar.php" class="btn btn-primary mb-3">Adicionar Produto</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Descrição</th>
            <th>Preço</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
        <tr>
            <td><?php echo $produto['id']; ?></td>
            <td><?php echo htmlspecialchars($produto['nome']); ?></td>
            <td><?php echo htmlspecialchars($produto['descricao']); ?></td>
            <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
            <td><?php echo $produto['estoque']; ?></td>
            <td>
                <a href="editar.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="remover.php?id=<?php echo $produto['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Remover</a>
                <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#estoqueModal<?php echo $produto['id']; ?>">Estoque</button>
                
                <!-- Modal para atualizar estoque -->
                <div class="modal fade" id="estoqueModal<?php echo $produto['id']; ?>" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Atualizar Estoque</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="atualizar_estoque.php" method="post">
                                <div class="modal-body">
                                    <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">
                                    <div class="mb-3">
                                        <label class="form-label">Quantidade (+/-)</label>
                                        <input type="number" class="form-control" name="quantidade" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Atualizar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>