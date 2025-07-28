<?php 
include '../includes/config.php';
include '../includes/functions.php';

// Verifica se o ID do cliente foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$cliente_id = intval($_GET['id']);
$cliente = getClienteById($cliente_id);

// Verifica se o cliente existe
if (!$cliente) {
    header("Location: index.php");
    exit();
}

// Processa o formulário de edição
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação do token CSRF
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Token CSRF inválido!");
    }
    
    // Sanitização dos dados
    $nome = htmlspecialchars($_POST['nome']);
    $endereco = htmlspecialchars($_POST['endereco']);
    $telefone = htmlspecialchars($_POST['telefone']);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    
    try {
        $stmt = $pdo->prepare("UPDATE clientes SET nome = ?, endereco = ?, telefone = ?, email = ? WHERE id = ?");
        if ($stmt->execute([$nome, $endereco, $telefone, $email, $cliente_id])) {
            $_SESSION['success'] = "Cliente atualizado com sucesso!";
            header("Location: index.php");
            exit();
        } else {
            $error = "Erro ao atualizar cliente.";
        }
    } catch (PDOException $e) {
        $error = "Erro no banco de dados: " . $e->getMessage();
    }
}

// Gera token CSRF
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
?>

<?php include '../includes/header.php'; ?>

<h2>Editar Cliente</h2>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>

<form method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
    
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" class="form-control" id="nome" name="nome" 
               value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="endereco" name="endereco" 
               value="<?php echo htmlspecialchars($cliente['endereco']); ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="text" class="form-control" id="telefone" name="telefone" 
               value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" 
               value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<?php include '../includes/footer.php'; ?>