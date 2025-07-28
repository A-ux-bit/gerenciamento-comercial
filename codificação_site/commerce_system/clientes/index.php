<?php 
include '../includes/config.php';
include '../includes/functions.php';

$clientes = getClientes();
?>

<?php include '../includes/header.php'; ?>

<h2>Clientes</h2>
<a href="adicionar.php" class="btn btn-primary mb-3">Adicionar Cliente</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Endereço</th>
            <th>Telefone</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clientes as $cliente): ?>
        <tr>
            <td><?php echo $cliente['id']; ?></td>
            <td><?php echo htmlspecialchars($cliente['nome']); ?></td>
            <td><?php echo htmlspecialchars($cliente['endereco']); ?></td>
            <td><?php echo htmlspecialchars($cliente['telefone']); ?></td>
            <td><?php echo htmlspecialchars($cliente['email']); ?></td>
            <td>
                <a href="editar.php?id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-warning">Editar</a>
                <a href="remover.php?id=<?php echo $cliente['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Remover</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>