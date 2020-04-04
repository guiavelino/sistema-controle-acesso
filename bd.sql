create database controle_de_acesso;
use controle_de_acesso;

create table usuarios(
	id_usuario int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	email varchar(80) not null,
    senha varchar(32) not null,
    telefone varchar(20) DEFAULT '(xx)xxxxx-xxxx',
    data_nascimento date DEFAULT '1970-01-01',
    genero varchar(30) DEFAULT 'Indefinido',
	imagem_usuario varchar(80) DEFAULT 'user.png'
	nivel_acesso varchar(13) not null  
);

create table moradores(
	id_morador int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf char(14) not null,
	telefone varchar(20) not null,
	apartamento char(5) not null,
	bloco char(1) not null
);

create table prestadores_servicos(
	id_prestador_servico int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf char(14) not null,
	telefone varchar(20) not null,
	apartamento char(5) not null,
	bloco char(1) not null,
	data_de_entrada datetime
);

create table visitantes(
	id_visitante int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf char(14) not null,
	telefone varchar(20) not null,
	apartamento char(5) not null,
	bloco char(1) not null,
	data_de_entrada datetime
);

create table encomendas(
	id_encomenda int primary key AUTO_INCREMENT not null,
	empresa varchar(60) not null,
	apartamento char(5) not null,
	bloco char(1) not null,
	data_de_entrada datetime
)

