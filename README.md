# Clube do Livro - JLA

Este repositório contém o código-fonte da plataforma web **Narrify - JLA**, desenvolvida com o objetivo de fomentar a leitura e o compartilhamento de resenhas literárias entre os alunos da instituição educacional Joaquim de Lima Avelino. O projeto oferece um ambiente virtual onde os participantes podem acessar, cadastrar e consultar resenhas de livros, promovendo o engajamento com a literatura.

## 1. Objetivo

O projeto visa fornecer uma solução digital simples e eficiente para organização de conteúdo literário produzido por estudantes, incentivando a leitura e a escrita crítica.

## 2. Funcionalidades

- Visualização de resenhas de livros
- Filtro por título do livro
- Sistema de login/autenticação
- Cadastro de novas resenhas (restrito a membros do clube ou usuários autorizados)
- Upload de imagens para resenhas
- Interface responsiva com navegação intuitiva

## 3. Tecnologias Utilizadas

- **Frontend:** HTML5, CSS3, JavaScript
- **Backend:** PHP
- **Banco de Dados:** MySQL
- **Bibliotecas e serviços utilizados:** 
    - PHPMailer - Envio de emails para processo de autenticação
    - API Cloudinary - Hospedagem de imagens

## 4. Estrutura de Diretórios
clube_do_livro-JLA/
├── css/ # Estilos personalizados
├── html/ # Arquivos html
├── img/ # Imagens utilizadas no site, como: variações da logo do clube e variações da logo da escola Joaquim de Lima Avelino
├── javascript/ # Scripts Javascript
├── php/ # Scripts PHP (arquivos de conexões, login, cadastro, para fazer resenhas, autenticação e etc.)
├── .gitignore # Para deixar irrastreável arquivos de conteúdo sensível
├── composer.json # Arquivo de configuração do composer
├── composer.lock # Versões travadas das dependências
├── LICENSE # Licença do projeto
└── README.md # Documento descritivo do projeto
