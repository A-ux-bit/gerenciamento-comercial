#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#define MAX_CLIENTES 100
#define MAX_PRODUTOS 100
#define MAX_PEDIDOS 100
#define MAX_COMPRAS 100
#define MAX_ITENS_PEDIDO 50

// ---- Estruturas de Dados ----

typedef struct {
    int id;
    char nome[100];
    char endereco[200];
    char telefone[20];
    char email[100];
} Cliente;

typedef struct {
    int id;
    char nome[100];
    char descricao[200];
    float preco;
    int estoque;
} Produto;

typedef struct {
    int produto_id;
    int quantidade;
    float preco_unitario;
} ItemPedido;

typedef struct {
    int id;
    int cliente_id;
    char data[20];
    ItemPedido itens[MAX_ITENS_PEDIDO];
    int num_itens;
    float total;
} Pedido;

typedef struct {
    int id;
    int produto_id;
    int quantidade;
    char data[20];
    float preco_total;
} Compra;

// ---- Variáveis globais de ID ----

int next_cliente_id = 1;
int next_produto_id = 1;
int next_pedido_id   = 1;
int next_compra_id   = 1;

// ---- Protótipos de Funções ----

void adicionar_cliente(Cliente *clientes, int *num_clientes);
void listar_clientes(Cliente *clientes, int num_clientes);
Cliente* buscar_cliente_por_id(Cliente *clientes, int num_clientes, int id);
void editar_cliente(Cliente *clientes, int num_clientes);
void remover_cliente(Cliente *clientes, int *num_clientes);

void adicionar_produto(Produto *produtos, int *num_produtos);
void listar_produtos(Produto *produtos, int num_produtos);
Produto* buscar_produto_por_id(Produto *produtos, int num_produtos, int id);
void editar_produto(Produto *produtos, int num_produtos);
void remover_produto(Produto *produtos, int *num_produtos);
void atualizar_estoque(Produto *produtos, int num_produtos, int produto_id, int quantidade);

void criar_pedido(Pedido *pedidos, int *num_pedidos, Cliente *clientes, int num_clientes, Produto *produtos, int num_produtos);
void listar_pedidos(Pedido *pedidos, int num_pedidos, Cliente *clientes, int num_clientes, Produto *produtos, int num_produtos);

void registrar_compra(Compra *compras, int *num_compras, Produto *produtos, int num_produtos);
void listar_compras(Compra *compras, int num_compras, Produto *produtos, int num_produtos);

void analisar_clientes(Cliente *clientes, int num_clientes, Pedido *pedidos, int num_pedidos);
void gerar_relatorio_vendas(Pedido *pedidos, int num_pedidos, Produto *produtos, int num_produtos);

// ---- main() ----

int main() {
    Cliente clientes[MAX_CLIENTES];
    int num_clientes = 0;
    Produto produtos[MAX_PRODUTOS];
    int num_produtos = 0;
    Pedido pedidos[MAX_PEDIDOS];
    int num_pedidos = 0;
    Compra compras[MAX_COMPRAS];
    int num_compras = 0;

    int opcao;
    do {
        printf("\n--- Sistema de Gerenciamento Comercial ---\n");
        printf("1. Gerenciar Clientes\n");
        printf("2. Gerenciar Produtos e Estoque\n");
        printf("3. Processar Pedidos de Vendas\n");
        printf("4. Acompanhar Atividades de Compras\n");
        printf("5. Analisar Dados de Clientes\n");
        printf("6. Gerar Relatorio de Vendas\n");
        printf("0. Sair\n");
        printf("Escolha uma opcao: ");
        if (scanf("%d", &opcao) != 1) {
            // esvazia buffer e força opção inválida
            int c;
            while ((c = getchar()) != '\n' && c != EOF);
            opcao = -1;
        }

        switch (opcao) {
            case 1: {
                int sub;
                do {
                    printf("\n--- Gerenciar Clientes ---\n");
                    printf("1. Adicionar Cliente\n");
                    printf("2. Listar Clientes\n");
                    printf("3. Editar Cliente\n");
                    printf("4. Remover Cliente\n");
                    printf("0. Voltar\n");
                    printf("Opcao: ");
                    if (scanf("%d", &sub) != 1) { while (getchar()!='\n'); sub=-1;}
                    switch (sub) {
                        case 1: adicionar_cliente(clientes, &num_clientes); break;
                        case 2: listar_clientes(clientes, num_clientes); break;
                        case 3: editar_cliente(clientes, num_clientes); break;
                        case 4: remover_cliente(clientes, &num_clientes); break;
                        case 0: printf("Voltando...\n"); break;
                        default: printf("Opcao invalida.\n");
                    }
                } while (sub != 0);
                break;
            }
            case 2: {
                int sub;
                do {
                    printf("\n--- Gerenciar Produtos ---\n");
                    printf("1. Adicionar Produto\n");
                    printf("2. Listar Produtos\n");
                    printf("3. Editar Produto\n");
                    printf("4. Remover Produto\n");
                    printf("5. Atualizar Estoque\n");
                    printf("0. Voltar\n");
                    printf("Opcao: ");
                    if (scanf("%d", &sub) != 1) { while (getchar()!='\n'); sub=-1;}
                    switch (sub) {
                        case 1: adicionar_produto(produtos, &num_produtos); break;
                        case 2: listar_produtos(produtos, num_produtos); break;
                        case 3: editar_produto(produtos, num_produtos); break;
                        case 4: remover_produto(produtos, &num_produtos); break;
                        case 5: {
                            int id, qtd;
                            printf("ID do produto: "); scanf("%d",&id);
                            printf("Qtd (+/-): "); scanf("%d",&qtd);
                            atualizar_estoque(produtos, num_produtos, id, qtd);
                        } break;
                        case 0: printf("Voltando...\n"); break;
                        default: printf("Opcao invalida.\n");
                    }
                } while (sub != 0);
                break;
            }
            case 3:
                criar_pedido(pedidos, &num_pedidos, clientes, num_clientes, produtos, num_produtos);
                break;
            case 4: {
                int sub;
                printf("\n--- Compras ---\n1. Registrar Compra\n2. Listar Compras\n0. Voltar\nOpcao: ");
                if (scanf("%d",&sub)!=1){while(getchar()!='\n'); sub=-1;}
                if (sub==1)      registrar_compra(compras, &num_compras, produtos, num_produtos);
                else if (sub==2) listar_compras(compras, num_compras, produtos, num_produtos);
                else printf("Voltando...\n");
                break;
            }
            case 5:
                analisar_clientes(clientes, num_clientes, pedidos, num_pedidos);
                break;
            case 6:
                gerar_relatorio_vendas(pedidos, num_pedidos, produtos, num_produtos);
                break;
            case 0:
                printf("Saindo...\n");
                break;
            default:
                printf("Opcao invalida.\n");
        }
    } while (opcao != 0);

    return 0;
}

// ---- Implementações ----

void adicionar_cliente(Cliente *clientes, int *num) {
    if (*num >= MAX_CLIENTES) { printf("Limite atingido.\n"); return; }
    clientes[*num].id = next_cliente_id++;
    while(getchar()!='\n');
    printf("Nome: ");   scanf(" %99[^\n]", clientes[*num].nome);
    printf("Endereco: "); scanf(" %199[^\n]", clientes[*num].endereco);
    printf("Telefone: "); scanf(" %19[^\n]", clientes[*num].telefone);
    printf("Email: ");   scanf(" %99[^\n]", clientes[*num].email);
    (*num)++;
    printf("Cliente adicionado!\n");
}

void listar_clientes(Cliente *c, int n) {
    if (!n) {
        printf("Nenhum cliente cadastrado.\n");
        return;
    }

    printf("\n=== LISTA DE CLIENTES ===\n");
    for(int i = 0; i < n; i++) {
        printf("ID:%d | %s | %s | %s | %s\n",
               c[i].id,
               c[i].nome,
               c[i].endereco,
               c[i].telefone,
               c[i].email);
    }
}


Cliente* buscar_cliente_por_id(Cliente *c, int n, int id) {
    for(int i=0;i<n;i++) if(c[i].id==id) return &c[i];
    return NULL;
}

void editar_cliente(Cliente *c, int n) {
    int id; printf("ID p/ editar: "); if(scanf("%d",&id)!=1){while(getchar()!='\n');return;}
    Cliente *cli = buscar_cliente_por_id(c,n,id);
    if(!cli){ printf("Nao encontrado.\n"); return; }
    while(getchar()!='\n');
    char buf[200];
    printf("Nome (%s): ", cli->nome);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        buf[strcspn(buf,"\n")]=0; strncpy(cli->nome, buf, sizeof(cli->nome)-1);
    }
    printf("Endereco (%s): ", cli->endereco);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        buf[strcspn(buf,"\n")]=0; strncpy(cli->endereco, buf, sizeof(cli->endereco)-1);
    }
    printf("Telefone (%s): ", cli->telefone);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        buf[strcspn(buf,"\n")]=0; strncpy(cli->telefone, buf, sizeof(cli->telefone)-1);
    }
    printf("Email (%s): ", cli->email);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        buf[strcspn(buf,"\n")]=0; strncpy(cli->email, buf, sizeof(cli->email)-1);
    }
    printf("Cliente editado!\n");
}

void remover_cliente(Cliente *c, int *n) {
    int id; printf("ID p/ remover: "); if(scanf("%d",&id)!=1){while(getchar()!='\n');return;}
    int idx=-1;
    for(int i=0;i<*n;i++) if(c[i].id==id){ idx=i; break; }
    if(idx<0){ printf("Nao encontrado.\n"); return; }
    for(int i=idx;i<(*n)-1;i++) c[i]=c[i+1];
    (*n)--; printf("Removido!\n");
}

void adicionar_produto(Produto *p, int *n) {
    if (*n >= MAX_PRODUTOS) { printf("Limite atingido.\n"); return; }
    p[*n].id = next_produto_id++;
    while(getchar()!='\n');
    printf("Nome: ");   scanf(" %99[^\n]", p[*n].nome);
    printf("Desc: ");   scanf(" %199[^\n]", p[*n].descricao);
    printf("Preco: ");  scanf("%f",&p[*n].preco);
    printf("Estoque: ");scanf("%d",&p[*n].estoque);
    (*n)++;
    printf("Produto adicionado!\n");
}

void listar_produtos(Produto *p, int n) {
    if (!n) { printf("Nenhum produto.\n"); return; }
    for(int i=0;i<n;i++)
        printf("ID:%d  %s (R$%.2f) [%d]\n", p[i].id, p[i].nome, p[i].preco, p[i].estoque);
}

Produto* buscar_produto_por_id(Produto *p, int n, int id) {
    for(int i=0;i<n;i++) if(p[i].id==id) return &p[i];
    return NULL;
}

void editar_produto(Produto *p, int n) {
    int id; printf("ID p/ editar: "); if(scanf("%d",&id)!=1){while(getchar()!='\n');return;}
    Produto *prd = buscar_produto_por_id(p,n,id);
    if(!prd){ printf("Nao encontrado.\n"); return; }
    while(getchar()!='\n');
    char buf[200];
    printf("Nome (%s): ", prd->nome);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        buf[strcspn(buf,"\n")]=0; strncpy(prd->nome, buf, sizeof(prd->nome)-1);
    }
    printf("Desc (%s): ", prd->descricao);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        buf[strcspn(buf,"\n")]=0; strncpy(prd->descricao, buf, sizeof(prd->descricao)-1);
    }
    printf("Preco (%.2f): ", prd->preco);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        float np; if(sscanf(buf,"%f",&np)==1) prd->preco=np;
    }
    printf("Estoque (%d): ", prd->estoque);
    if (fgets(buf,sizeof(buf),stdin) && buf[0]!='\n') {
        int ne; if(sscanf(buf,"%d",&ne)==1) prd->estoque=ne;
    }
    printf("Produto editado!\n");
}

void remover_produto(Produto *p, int *n) {
    int id; printf("ID p/ remover: "); if(scanf("%d",&id)!=1){while(getchar()!='\n');return;}
    int idx=-1;
    for(int i=0;i<*n;i++) if(p[i].id==id){ idx=i; break; }
    if(idx<0){ printf("Nao encontrado.\n"); return; }
    for(int i=idx;i<(*n)-1;i++) p[i]=p[i+1];
    (*n)--; printf("Removido!\n");
}

void atualizar_estoque(Produto *p, int n, int id, int qtd) {
    Produto *prd = buscar_produto_por_id(p,n,id);
    if(!prd) { printf("Nao encontrado.\n"); return; }
    if(prd->estoque+qtd<0) { printf("Estoque insuficiente.\n"); return; }
    prd->estoque += qtd;
    printf("Estoque atualizado: %d\n", prd->estoque);
}

void criar_pedido(Pedido *pedidos, int *num_pedidos, Cliente *clientes, int num_clientes, Produto *produtos, int num_produtos) {
    if (*num_pedidos >= MAX_PEDIDOS) {
        printf("Limite de pedidos atingido.\n");
        return;
    }
    int cid;
    printf("ID do cliente: "); scanf("%d",&cid);
    Cliente *cli = buscar_cliente_por_id(clientes,num_clientes,cid);
    if(!cli){ printf("Cliente nao encontrado.\n"); return; }
    Pedido *pd = &pedidos[*num_pedidos];
    pd->id = next_pedido_id++;
    pd->cliente_id = cid;
    pd->num_itens = 0;
    pd->total = 0.0f;
    int pid;
    while (1) {
        if (pd->num_itens >= MAX_ITENS_PEDIDO) { printf("Limite de itens.\n"); break; }
        printf("ID do produto (0 p/ fim): "); scanf("%d",&pid);
        if (pid==0) break;
        Produto *pr = buscar_produto_por_id(produtos,num_produtos,pid);
        if(!pr){ printf("Produto nao encontrado.\n"); continue; }
        int q; printf("Qtd: "); scanf("%d",&q);
        if(q>0 && pr->estoque>=q) {
            ItemPedido *it = &pd->itens[pd->num_itens++];
            it->produto_id = pid;
            it->quantidade  = q;
            it->preco_unitario = pr->preco;
            pd->total += q * pr->preco;
            pr->estoque -= q;
        } else printf("Qtd invalida ou sem estoque.\n");
    }
    if(pd->num_itens>0) {
        (*num_pedidos)++;
        printf("Pedido #%d criado. Total=%.2f\n", pd->id, pd->total);
    } else {
        printf("Pedido vazio. Nao criado.\n");
    }
}

void listar_pedidos(Pedido *pedidos, int num_pedidos, Cliente *clientes, int num_clientes, Produto *produtos, int num_produtos) {
    if(!num_pedidos){ printf("Nenhum pedido.\n"); return; }
    for(int i=0;i<num_pedidos;i++){
        Pedido *pd = &pedidos[i];
        Cliente *cli = buscar_cliente_por_id(clientes,num_clientes,pd->cliente_id);
        printf("Pedido %d p/ %s: total=%.2f\n", pd->id,
               cli?cli->nome:"(desconhecido)", pd->total);
        for(int j=0;j<pd->num_itens;j++){
            ItemPedido *it = &pd->itens[j];
            Produto *pr = buscar_produto_por_id(produtos,num_produtos,it->produto_id);
            printf("  - %s x%d @%.2f\n",
                   pr?pr->nome:"(desconhecido)", it->quantidade, it->preco_unitario);
        }
    }
}

void registrar_compra(Compra *compras, int *num_compras, Produto *produtos, int num_produtos) {
    if (*num_compras >= MAX_COMPRAS) {
        printf("Limite de compras atingido.\n");
        return;
    }
    int pid; printf("ID do produto: "); scanf("%d",&pid);
    Produto *pr = buscar_produto_por_id(produtos,num_produtos,pid);
    if(!pr){ printf("Produto nao encontrado.\n"); return; }
    int q; printf("Qtd comprada: "); scanf("%d",&q);
    if(q<=0){ printf("Qtd invalida.\n"); return; }
    Compra *cp = &compras[*num_compras];
    cp->id = next_compra_id++;
    cp->produto_id = pid;
    cp->quantidade  = q;
    cp->preco_total = q * pr->preco;
    pr->estoque += q;
    (*num_compras)++;
    printf("Compra registrada.\n");
}

void listar_compras(Compra *compras, int num_compras, Produto *produtos, int num_produtos) {
    if(!num_compras){ printf("Nenhuma compra.\n"); return; }
    for(int i=0;i<num_compras;i++){
        Compra *cp = &compras[i];
        Produto *pr = buscar_produto_por_id(produtos,num_produtos,cp->produto_id);
        printf("Compra %d: %s x%d total=%.2f\n",
               cp->id,
               pr?pr->nome:"(desconhecido)",
               cp->quantidade,
               cp->preco_total);
    }
}

void analisar_clientes(Cliente *clientes, int num_clientes, Pedido *pedidos, int num_pedidos) {
    if(!num_clientes){ printf("Sem clientes.\n"); return; }
    if(!num_pedidos){ printf("Sem pedidos.\n"); return; }
    int *cont = calloc(num_clientes, sizeof(int));
    int max = 0;
    for(int i=0;i<num_pedidos;i++){
        Pedido *pd = &pedidos[i];
        for(int j=0;j<num_clientes;j++){
            if(clientes[j].id == pd->cliente_id){
                cont[j]++;
                if(cont[j]>max) max = cont[j];
            }
        }
    }
    if(max==0){
        printf("Nenhum pedido feito.\n");
    } else {
        printf("Cliente(s) mais ativo(s):\n");
        for(int j=0;j<num_clientes;j++){
            if(cont[j]==max)
                printf(" - %s: %d pedidos\n", clientes[j].nome, cont[j]);
        }
    }
    free(cont);
}

void gerar_relatorio_vendas(Pedido *pedidos, int num_pedidos, Produto *produtos, int num_produtos) {
    if(!num_pedidos){ printf("Sem pedidos.\n"); return; }
    float total=0;
    for(int i=0;i<num_pedidos;i++) total += pedidos[i].total;
    printf("Total pedidos: %d  Valor vendido: %.2f\n", num_pedidos, total);
    for(int p=0;p<num_produtos;p++){
        int soma_q=0;
        float soma_v=0;
        for(int i=0;i<num_pedidos;i++){
            for(int j=0;j<pedidos[i].num_itens;j++){
                if(pedidos[i].itens[j].produto_id == produtos[p].id){
                    soma_q += pedidos[i].itens[j].quantidade;
                    soma_v += pedidos[i].itens[j].quantidade * pedidos[i].itens[j].preco_unitario;
                }
            }
        }
        if(soma_q>0)
            printf(" - %s: %d unid., R$%.2f\n", produtos[p].nome, soma_q, soma_v);
    }
}
