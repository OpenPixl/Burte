
const FiltersForms = document.getElementById('filters');
document.querySelectorAll('#filters input').forEach(input => {
    input.addEventListener('change', () => {
        console.log('Ok')
        // j'intercepte les clics et ses données.
        const Form = new FormData(FiltersForms);
        // construction de la "QueryString"
        const Params = new URLSearchParams();
        //alimentation de la "QueryString"
        Form.forEach((value,key) => {
            Params.append(key, value);
        })

        const url = '/gestapp/product/oneCat/filtercategory';

        axios
            .get(url + "?" + Params.toString())
            .then(response => {
                // rafraichissement du tableau
                const liste = document.getElementById('liste').innerHTML = response.data.liste;
                // Ajout d'un event sur Bouton de suppression dans la fenêtre modale
                document.querySelectorAll('a.page-link').forEach(function(link){
                    link.addEventListener('click', onClickPaginator)
                })
            })
    });
});

// Construction de la fonction lorsque l'utilisateur
function onClickPaginator(event){
    event.preventDefault();
    const urlPaginator = this.href
    const urlParams = urlPaginator.split('?')[1]
    const Params = new URLSearchParams(urlParams);
    const page = Params.get('page')
    const url = '/gestapp/product/oneCat/filtercategory'
    const cat = document.getElementById('listonecatproduct').dataset.natorcat;
    const idcat = document.getElementById('listonecatproduct').dataset.name;

    axios
        .get(url + "?" + cat +"=" + idcat + "&page=" + page )
        .then(response => {
            // rafraichissement du tableau
            const liste = document.getElementById('liste').innerHTML = response.data.liste;
            // Ajout d'un event sur Bouton de suppression dans la fenêtre modale
            document.querySelectorAll('a.page-link').forEach(function(link){
                link.addEventListener('click', onClickPaginator)
            })
        })
}

// Ajout d'un event sur Bouton de suppression dans la fenêtre modale
document.querySelectorAll('a.page-link').forEach(function(link){
    link.addEventListener('click', onClickPaginator)
})

