# Desafio Inicial para SRE/DevOps

Repositório contendo o desafio inicial para estudo SRE/DevOps

## Objetivo
Avaliar conhecimentos básicos de provisionamento de infraestrutura.

## Tarefas do Desafio

### Configuração de Servidor Linux
- Criar uma conta no Github
- Provisionar uma máquina virtual ou usar uma instância em algum provedor de nuvem (AWS, Azure, GCP ou DigitalOcean).
- Configurar uma aplicação web simples (ex: servidor Apache ou Nginx servindo uma página HTML estática + Mysql).
- Expor a aplicação na porta 80 usando um firewall configurado corretamente.
- Necessário subir o Dockerfile para o Github e criar um README para o projeto.

# Montando o ambiente

Siga as instruções abaixo para configurar um ambiente Docker com Apache e MySQL.

## Requisitos

- Docker instalado na sua máquina.
- Acesso à linha de comando.

## Passo a Passo

### 1. Clonando repositório do desafio.
Clone esse repositório na sua maquina.

```
git clone https://github.com/maxwellwolf/SRE.git
```

Acesse a pasta SRE

```
cd ./SRE
```

### 2. Remover imagens, contêineres, redes e volumes antigos

Removendo imagens, contêineres, redes e volumes antigos para evitar conflitos:

```bash
docker rm db --force
```
```bash
docker rm www --force
```
```bash
docker network rm SRE
```
```bash
docker volume remove db_volume
```
```bash
docker rmi php_apache
```
```bash
docker rmi db_mysql
```
### 3. Criar uma rede Docker
Crie uma nova rede bridge chamada SRE para conectarmos nossos containers:

```bash
docker network create SRE
```

### 4. Criar um volume Docker
Crie um novo volume chamado db_volume, ele será usado como persistência para nosso banco de dados:

```bash
docker volume create db_volume
```

### 5. Construir as imagens Docker
Construa as imagens Docker para Apache e MySQL a partir dos Dockerfiles nos diretórios ./apache/ e ./mysql/:

```bash
docker build -t maxwellwolf/apache:1.0 ./apache/
```
```bash
docker build -t maxwellwolf/mysql:1.0 ./mysql/
```
### 6. Executar os contêineres Docker
Execute o contêiner MySQL:

```bash
docker run --name db --network=SRE -v db_volume:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=987654321 -e MYSQL_PASSWORD=admin -p 3306:3306 -d maxwellwolf/mysql:1.0
```
Execute o contêiner Apache:

```bash
docker run --name www --network=SRE -p 80:80 -d maxwellwolf/apache:1.0
```
## Conclusão
Após seguir esses passos, você terá um ambiente Docker configurado com Apache e MySQL. Acesse o http://localhost pelo navegador para acessar a página com o crud da tabela Pessoa.

![Crud tabela Pessoa](https://github.com/maxwellwolf/SRE/blob/main/amostra.PNG)


Alternativamente, se estiver em um ambiente unix, você poderá executar o script init.sh para configurar o ambiente automaticamente.

