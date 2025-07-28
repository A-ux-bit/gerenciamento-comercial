#include <iostream>
#include <vector>
#include <string>
#include <map>
#include <algorithm>

using namespace std;

// ===================== CLASSES ===================== //

struct Cliente {
    int id;
    string nome;
    string email;
    int totalCompras;
};

struct Produto {
    int id;
    string nome;
    int estoque;
};

struct Venda {
    int clienteId;
    int produtoId;
    int quantidade;
};

// ===================== BANCOS DE DADOS ===================== //

vector<Cliente> clientes;
vector<Produto> produtos;
vector<Venda> vendas;

int gerarIdCliente() {
    static int id = 1;
    return id++;
}

int gerarIdProduto() {
    static int id = 1;
    return id++;
}

// ===================== FUNÇÕES DE CLIENTE ===================== //

void cadastrarCliente() {
    Cliente c;
    c.id = gerarIdCliente();
    cout << "Nome do cliente: ";
    cin.ignore();
    getline(cin, c.nome);
    cout << "Email: ";
    getline(cin, c.email);
    c.totalCompras = 0;
    clientes.push_back(c);
    cout << "Cliente cadastrado com sucesso!\n";
}

void alterarCliente() {
    int id;
    cout << "ID do cliente a alterar: ";
    cin >> id;
    for (int i = 0; i < clientes.size(); i++) {
        if (clientes[i].id == id) {
            cout << "Novo nome: ";
            cin.ignore();
            getline(cin, clientes[i].nome);
            cout << "Novo email: ";
            getline(cin, clientes[i].email);
            cout << "Cliente atualizado com sucesso!\n";
            return;
        }
    }
    cout << "Cliente não encontrado.\n";
}

void excluirCliente() {
    int id;
    cout << "ID do cliente a excluir: ";
    cin >> id;
    for (int i = 0; i < clientes.size(); i++) {
        if (clientes[i].id == id) {
            clientes.erase(clientes.begin() + i);
            cout << "Cliente excluído.\n";
            return;
        }
    }
    cout << "Cliente não encontrado.\n";
}

// ===================== FUNÇÕES DE PRODUTO ===================== //

void cadastrarProduto() {
    Produto p;
    p.id = gerarIdProduto();
    cout << "Nome do produto: ";
    cin.ignore();
    getline(cin, p.nome);
    cout << "Quantidade em estoque: ";
    cin >> p.estoque;
    produtos.push_back(p);
    cout << "Produto cadastrado!\n";
}

void movimentarEstoque() {
    int id, quantidade;
    char tipo;
    cout << "ID do produto: ";
    cin >> id;
    cout << "(E)ntrada ou (S)aída? ";
    cin >> tipo;
    cout << "Quantidade: ";
    cin >> quantidade;

    for (int i = 0; i < produtos.size(); i++) {
        if (produtos[i].id == id) {
            if (tipo == 'E' || tipo == 'e') produtos[i].estoque += quantidade;
            else if (tipo == 'S' || tipo == 's') produtos[i].estoque -= quantidade;
            cout << "Movimentação realizada.\n";
            return;
        }
    }
    cout << "Produto não encontrado.\n";
}

// ===================== FUNÇÕES DE VENDA ===================== //

void registrarVenda() {
    int clienteId, produtoId, quantidade;
    cout << "ID do cliente: ";
    cin >> clienteId;
    cout << "ID do produto: ";
    cin >> produtoId;
    cout << "Quantidade: ";
    cin >> quantidade;

    for (int i = 0; i < produtos.size(); i++) {
        if (produtos[i].id == produtoId && produtos[i].estoque >= quantidade) {
            produtos[i].estoque -= quantidade;
            Venda v;
            v.clienteId = clienteId;
            v.produtoId = produtoId;
            v.quantidade = quantidade;
            vendas.push_back(v);
            for (int j = 0; j < clientes.size(); j++) {
                if (clientes[j].id == clienteId) {
                    clientes[j].totalCompras += quantidade;
                }
            }
            cout << "Venda registrada com sucesso.\n";
            return;
        }
    }
    cout << "Venda falhou. Verifique o estoque ou IDs.\n";
}

// ===================== ANÁLISE DE DADOS ===================== //

bool compararClientes(Cliente a, Cliente b) {
    return a.totalCompras > b.totalCompras;
}

void mostrarClientesValiosos() {
    sort(clientes.begin(), clientes.end(), compararClientes);
    cout << "Clientes mais valiosos:\n";
    for (int i = 0; i < clientes.size(); i++) {
        cout << clientes[i].nome << " - Compras: " << clientes[i].totalCompras << endl;
    }
}

void alertasEstoque() {
    for (int i = 0; i < produtos.size(); i++) {
        if (produtos[i].estoque < 5) {
            cout << "[ALERTA] Produto em baixo estoque: " << produtos[i].nome << " (" << produtos[i].estoque << ")\n";
        }
    }
}

void pedidosPendentes() {
    map<int, int> pendencias;
    for (int i = 0; i < vendas.size(); i++) {
        for (int j = 0; j < produtos.size(); j++) {
            if (produtos[j].id == vendas[i].produtoId && produtos[j].estoque == 0) {
                pendencias[vendas[i].clienteId]++;
            }
        }
    }
    map<int, int>::iterator it;
    for (it = pendencias.begin(); it != pendencias.end(); ++it) {
        cout << "Cliente ID " << it->first << " tem " << it->second << " pendência(s) de produto em falta.\n";
    }
}

// ===================== MENU ===================== //

void menu() {
    int op;
    do {
        cout << "\n1. Cadastrar Cliente\n2. Alterar Cliente\n3. Excluir Cliente\n4. Cadastrar Produto\n5. Movimentar Estoque\n6. Registrar Venda\n7. Análise de Clientes Valiosos\n8. Alertas de Estoque\n9. Pedidos Pendentes\n0. Sair\nOpção: ";
        cin >> op;
        switch (op) {
            case 1: cadastrarCliente(); break;
            case 2: alterarCliente(); break;
            case 3: excluirCliente(); break;
            case 4: cadastrarProduto(); break;
            case 5: movimentarEstoque(); break;
            case 6: registrarVenda(); break;
            case 7: mostrarClientesValiosos(); break;
            case 8: alertasEstoque(); break;
            case 9: pedidosPendentes(); break;
        }
    } while (op != 0);
}

int main() {
    menu();
    return 0;
}

