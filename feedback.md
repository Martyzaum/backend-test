# Feedback

Esse documento visa coletar feedbacks sobre o teste de desenvolvimento. Desde o início do teste até a entrega do projeto.

## Antes de Iniciar o Teste

1 - Fale sobre suas primeiras impressões do Teste:

> De primeira vista foi algo bem simples, dois cruds basicamente, mas nunca trabalhei com testes no laravel, então terei que aprender mas não sera um problema

2 - Tempo estimado para o teste:

> 12 Horas

3 - Qual parte você no momento considera mais difícil?

> Aprender e Realizar os testes

4 - Qual parte você no momento considera que levará mais tempo?

> Aprender e Realizar os testes

5 - Por onde você pretende começar?

> Criação das Tabelas e CRUD para os redirects

## Após o Teste

1 - O que você achou do teste?

> Achei bem tranquilo, o mais dificil foi realizar os testes que eu nunca fiz usando laravel

2 - Menos

> Resposta

3 - Teve imprevistos? Quais?

> Os testes realmente não sairam como esperado

4 - Existem pontos que você gostaria de ter melhorado?

> Os testes, por mais que eu tentasse e checasse na api, as vezes os testes passavam, as vezes não, isso somado a minha falta de tempo para melhorar esses testes me frustaram bastante

5 - Quais falhas você encontrou na estrutura do projeto?

> usar um hash com base no id incrimental acaba gerando erro de chave duplicada, pois o "code" não deve ser duplicado

## Considerações pessoais

> Devido a falta de tempo pessoal para realizar o teste, fiz ele em duas sessões de domingo das 15:00 as 20:00, e das 02:00 as 04:20 realizei factories, seeder e os testes
> Foi bem frustante pra mim não conseguir realizar os testes corretamente, por mais que testando via api o resultado esteja correto, acredito que com um pouco mais de tempo resolveria esse problema
> Realizei o cache de algumas consultas e também adicionei funções que removem o cache após update/delete, para manter sempre atualizado
> Tomei a liberdade de criar um docker compose e um dockerfile para a aplicação, também deixei um desenho da estrutura do projeto que usei para me guiar durante o desenvolvimento
> Como padrão de projeto geralmente crio uma camada de dominio, mas esse era simples então não foi necessario, tipei todas as funções, todas escritas em inglês, sempre tento deixar o código limpo e reutilizavel
> Ultilizei o padrão de branchs main e develop, com base na develop criei branchs de feature e fui adicionado aos poucos
> Fiz os commitos todos em inglês por padrão, como fiz em pouco tempo fiz poucos commits e talvez eles não estejam tao detalhados
> Adicionei Scrips bash para facilitar as seeders e testes

> Obrigado pela oportunidade, espero que gostem do meu teste e aguardo feedback =D
