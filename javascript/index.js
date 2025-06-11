const div = document.getElementById('javascript');
let conteudos = [];
let index = 0;

fetch('index.csv')
    .then(response => response.text())
    .then(text => {
        const linhas = text.trim().split('\n').slice(1);
        conteudos = linhas.map(linha => {
            const [titulo, ...descricaoArr] = linha.split(',');
            const descricao = descricaoArr.join(',');
            return {titulo: titulo.trim(), descricao: descricao.trim()};
        });
        mostrarConteudo();
        setInterval(mostrarConteudo, 5000);
    });

function mostrarConteudo() {
    if (conteudos.length === 0) return;

    const {titulo, descricao} = conteudos[index];
    div.innerHTML = `<h3>${titulo}</h3><p>${descricao}</p>`;
    
    index = (index + 1) % conteudos.length;
}