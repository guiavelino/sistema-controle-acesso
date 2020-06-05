drop database if exists controle_de_acesso;
create database if not exists controle_de_acesso;
use controle_de_acesso;

create table usuarios(
	id_usuario int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	email varchar(80) not null unique,
	cpf char(14) not null unique,
    senha varchar(32) not null,
    telefone varchar(20) DEFAULT '(99) 9999-99999',
    data_nascimento date DEFAULT '1970-01-01',
    genero varchar(30) DEFAULT 'Indefinido',
	imagem_usuario varchar(80) DEFAULT 'user.png',
	nivel_acesso varchar(13) not null  
);

create table moradores(
	id_morador int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf char(14) not null unique,
	telefone varchar(20) not null,
	apartamento varchar(5) not null,
	bloco char(1) not null
);

create table prestadores_servicos(
	id_prestador_servico int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf char(14) not null,
	apartamento varchar(13),
	bloco varchar(13),
	data_entrada datetime default CURRENT_TIMESTAMP,
	data_saida datetime
);

create table visitantes_cadastrados(
	id_visitante int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf char(14) unique not null,
	rg varchar(14) not null,
	uf varchar(2) not null
);

create table visitantes(
	id_visitante int primary key AUTO_INCREMENT not null,
	nome varchar(60) not null,
	cpf_rg varchar(14) not null,
	uf varchar(2) not null,
	apartamento varchar(5) not null,
	bloco char(1) not null,
	data_entrada datetime default CURRENT_TIMESTAMP,
	data_saida datetime,
	fk_id_visitante int,
    foreign key(fk_id_visitante) references visitantes_cadastrados(id_visitante) on delete cascade
);

create table encomendas(
	id_encomenda int primary key AUTO_INCREMENT not null,
	empresa varchar(60) not null,
	apartamento varchar(5) not null,
	bloco char(1) not null,
	data_entrega datetime default CURRENT_TIMESTAMP
);

create table eventos (
    id_evento int primary key AUTO_INCREMENT not null,
	cpf char(14) not null,
    titulo_evento varchar(60) not null,
    inicio_evento datetime not null,
    fim_evento datetime not null,
	cor varchar(10) not null
)