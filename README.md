Integrando a API da CompuFácil via SDK PHP
==========================================

A API da CompuFácil segue o padrão RPC. E o tráfego de dados é
através de JSON.

Ambientes
---------

Existem dois ambiente servindo a API. O ambiente de homologação e
de produção.

- **Produção**: https://app.compufacil.com.br
- **Homologação**: http://homolog.compufacil.com.br

Padrão dos serviços
-------------------

Todos os serviços seguem a seguinte nomenclatura:

```sh
$AMBIENTE/rpc/v1/$MODULO.$SERVICO
```

Módulo e serviço variam de acordo com o que parte do sistema está sendo utilizada.

### Exemplo:

```sh
#listar nfe's
http://homolog.compufacil.com.br/rpc/v1/fiscal.get-nfe
#criar nfe
http://homolog.compufacil.com.br/rpc/v1/fiscal.post-nfe
#atualizar nfe
http://homolog.compufacil.com.br/rpc/v1/fiscal.put-nfe
#deletar nfe
http://homolog.compufacil.com.br/rpc/v1/fiscal.delete-nfe

```

Autenticação
------------

Primeiramente instanciamos a classe passando as configurações do ambiente e a versão,
se caso não passarmos ela pegara os valores default que sempre
será ambiente de `homolog` e a última versão estável da API.

seguindo a autenticação se dá através da função `signUp`:

```php
$cf = new Compufacil([
    'environment' => 'homolog',
    'version' => '1'
]);

$result = $cf->signIn('test@sdk.com', '123456');
print_r($result);
```

Na resposta de uma autenticação com sucesso conterá o token que
dever ser trafegado nas requisições autenticadas.

```json
{
    "status": 1,
    "access_token": "1be2b89c92aff78f9ffae8b408d80b2c2d8bcf0a",
    "default_lang": "pt_BR",
    "is_admin": true
}

```

Enquanto mantivermos a classe o token ficara armazenado no estado da mesma

Serviços
--------

Os demais serviços seguem o mesmo padrão da autenticação, com a
única diferença que trafegam o token no header *Authorization-Compufacil*
e utilizamos agora a função auxiliar `rpcService`

### Exemplo fazendo o CRUD de receitas

**Lista receitas**

```php
$revenues = $cf->rpcService('finance.get-revenue');
print_r($revenues); // []
```

**Cria receita**

```php
$revenue = $cf->rpcService(
    'finance.post-revenue',
    [
        'description' => 'teste teste',
        'value' => 666
    ]
);
print_r($revenue); // ['id' => 152611]
```

**Pega 1 receita detalhada**

```php
$revenue = $cf->rpcService(
    'finance.get-revenue',
    [
        'id' => $revenue['íd']
    ]
);
print_r($revenue); // ['id' => 152611, 'description' => 'test...]
```

**Atualiza uma receita**

```php
$revenue = $cf->rpcService(
    'finance.put-revenue',
    [
        'id' => $revenue['id'],
        'description' => 'teste update',
        'value' => 999
    ]
);
print_r($revenue); // ['id' => 152611]
```

**Deleta receita**

```php
$revenue = $cf->rpcService(
    'finance.delete-revenue',
    [
        'id' => $revenue['íd']
    ]
);
print_r($revenue); // ['id' => 152611]
```

Swagger
-------

Para uma referência completa dos serviços você pode consultar
[o nosso swager]( http://developer.compufacil.com.br/api)
. Lá também é possível executar os serviços
diretamente contra homolog.

[Nosso blog](https://techblog.compufacil.com.br/)

Contato
-------

Qualquer dúvida sobre a API entre em contato com nosso time
técnico. Através do e-mail contato@compufacil.com.br.
