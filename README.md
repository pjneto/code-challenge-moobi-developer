# Desafio Moobi Tech

A entrega deve ser realizada em até 10 dias, caso não seja realizada o candidato está automaticamente desclassificado.


### A loja de brinquedos

A loja de brinquedos de sua cidade está expandindo suas vendas através da internet. Para isso você foi contratado para escrever o software que realizará o registro dessas vendas.

#### Produtos

Serviço de cadastro de produtos que deve ser possível cadastrar, localizar, atualizar e inativá-lo. Os campos necessários para o cadastro de produtos fica a seu critério, podendo ou não justificar a escolha dos campos. Como a loja também será aberta para revendedores individuais, campos personalizados também podem ser adicionados no cadastro de produtos para qualquer revendedor, seu software deverá estar preparado para receber tais informações.

#### Pedidos

Serviço de cadastro de pedidos. As informações do pedido consistem em um identificador, data do pedido, forma de pagamento (débito, cartão de crédito, boleto bancário), valor total da venda, número de parcelas (quando o pagamento é via cartão de crédito) e lista de produtos do pedido. 

Obs: A depender da forma de pagamento temos alguns diferenciais.

- Cartão de crédito: Valor integral da venda, deve ser registrado o valor das parcelas;
- Débito: Valor da venda deve ter um desconto de 10%;
- Boleto bancário: Valor da venda deve ter um desconto de 5%.

### Requisitos básicos

- Só deve ser possível parcelar compras no cartão de crédito;
- Decrementar o estoque do produto;
- Não deve ser possível registrar um pedido onde não temos o produto no estoque;
- Após a realização do pedido deve ser simulado a chamada de um método para realizar o envio de um SMS e de um e-mail para o cliente.

### Observações

- Não é necessário criar interface visual para o software;
- É necessário escrever testes para o software;
- Os dados devem ser armazenados em um database mysql (enviar código para criação do mesmo);

### Avaliação

- Atender aos requisitos do projeto
- Legibilidade, simplicidade e flexibilidade da solução
- Cobertura de testes

Crie um fork desse repositório e nos envie um pull request.