<?php include 'includes/header.php'; ?>

<div class="jumbotron text-center">
    <h1 class="display-4">Bem-vindo ao Sistema Comercial</h1>
    <p class="lead">Gerencie clientes, produtos, pedidos e compras em um único lugar</p>
    <hr class="my-4">
    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Clientes</h5>
                    <p class="card-text">Gerencie seus clientes</p>
                    <a href="clientes/" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Produtos</h5>
                    <p class="card-text">Gerencie seu estoque</p>
                    <a href="produtos/" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pedidos</h5>
                    <p class="card-text">Processe vendas</p>
                    <a href="pedidos/" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Compras</h5>
                    <p class="card-text">Registre compras</p>
                    <a href="compras/" class="btn btn-light">Acessar</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>