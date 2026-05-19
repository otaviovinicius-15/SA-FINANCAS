# 💵 SA-FINANÇAS (Gestão360)

Finanças pessoais

---

## 📊 Estrutura Organizacional
![Estrutura Organizacional](pictures/Estrutura-Organizacional.png)

---

## 🧩 Business Model Canvas
🔗 https://canva.link/4ywbf9q97dq1mel

---

## 🌍 Área de Atuação
Finanças pessoais

---

## ⚠️ Problema
Falta de controle e organização das finanças pessoais. 

---

## 💡 Solução
Sistema de controle financeiro:

- O sistema de finanças pessoais resolve o problema da falta de controle financeiro, ajudando usuários a organizar receitas, despesas, metas e contas, permitindo um melhor planejamento e evitando gastos desnecessários. 

---

## 🖼️ Wireframes e Fluxo de Telas

Abaixo estão apresentados os protótipos de tela do sistema **Gestão360**, divididos por fluxos de interação do usuário.

### 1. Autenticação e Visão Geral (Dashboard)
Este bloco compreende as telas iniciais de acesso e o painel principal de controle do usuário após o login.
* **Tela de Login:** Interface limpa contendo campos para e-mail, senha e botão de entrada, além de link para cadastro.
* **Tela de Cadastro:** Formulário para novos usuários com validação de confirmação de senha.
* **Dashboard Principal:** Exibição do Saldo Atual (Current Balance), Total de Receitas (Total Income) e Total de Despesas (Total Expenses). Inclui atalhos rápidos para inserção de dados, gráfico de visão mensal, gráfico de despesas por categoria e listagem das últimas transações efetuadas.

![Autenticação e Dashboard](pictures/wireframe%20\(1\).png)

### 2. Modais de Lançamento e Histórico de Transações
Este bloco demonstra as interações de inserção de dados e a listagem detalhada de movimentações.
* **Modal de Receita (Add Income):** Janela sobreposta para inserção de valor, descrição, data e seleção de categoria de entrada.
* **Modal de Despesa (Add Expense):** Janela sobreposta para inserção de valores de saída, descrição, data e categorização do gasto.
* **Tela de Transações:** Listagem tabular completa de todo o histórico financeiro do usuário, com filtros de busca e paginação.

![Modais e Transações](pictures/wireframe%20\(2\).png)

### 3. Categorização e Metas Financeiras
Exibição do gerenciamento avançado do sistema.
* **Tela de Categorias:** Organização visual dos gastos totais por áreas como Moradia (Housing), Alimentação (Food), Transporte (Transport), Lazer (Entertainment), Compras (Shopping) e Saúde (Healthcare), acompanhado por uma seção de *Category Insights* (indicadores percentuais).
* **Metas Financeiras (Financial Goals):** Painel de acompanhamento de objetivos de economia (Ex: Fundo de Emergência, Viagem para o Japão, Novo Computador), exibindo barras de progresso percentual, valor poupado e valor meta.

![Categorias e Metas Financeiras](pictures/wireframe%20\(3\).png)

---

## ⚖️ Regras de Negócio

- O e-mail do usuário não pode ser duplicado.
- O valor das receitas deve ser maior que zero.
- O valor de despesas deve ser maior que zero.
- Cada usuário poderá acessar apenas suas próprias informações.
- O saldo será calculado pela diferença entre receitas e despesas.
- Saldo = Receitas − Despesas
- Apenas usuários autenticados poderão acessar o sistema.
- Toda movimentação financeira deve possuir descrição e data.

---

## ⚙️ Requisitos Funcionais

- O sistema deve permitir o cadastro de usuários.
- O sistema deve permitir login de usuários.
- O sistema deve permitir cadastrar receitas financeiras.
- O sistema deve permitir cadastrar despesas financeiras.
- O sistema deve calcular o saldo financeiro do usuário.
- O sistema deve permitir visualizar receitas e despesas cadastradas.
- O sistema deve permitir cadastrar categorias financeiras.
- O sistema deve permitir criar metas financeiras.
- O sistema deve gerar relatórios financeiros.
- O sistema deve armazenar o histórico financeiro do usuário.

---

## 🛡️ Requisitos Não Funcionais

- O sistema deve carregar as informações rapidamente.
- O sistema deve possuir interface simples e fácil de usar.
- O sistema deve estar disponível para acesso sempre que necessário.
- O sistema deve armazenar corretamente os dados financeiros.
- O sistema deve funcionar nos principais navegadores.
- O sistema deve funcionar em computadores e celulares.
- O sistema deve permitir recuperação de dados em caso de falhas.

---

## 📊 Modelagem Lógica Banco
![Modelagem Lógica Banco](pictures/Modelagem-Lógica.png)