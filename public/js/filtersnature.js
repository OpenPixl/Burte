const FiltersForms = document.getElementById('filters');
document.querySelectorAll('#filters input').forEach(input => {
    input.addEventListener('change', () => {
        // j'intercepte les clics et ses données.
        const Form = new FormData(FiltersForms);
        // construction de la "QueryString"
        const Params = new URLSearchParams();
        //alimentation de la "QueryString"
        Form.forEach((value,key) => {
            Params.append(key, value);
        })

        const url = '/gestapp/product/oneNat/filternature';

        axios
            .get(url + "?" + Params.toString())
            .then(response => {
                // rafraichissement du tableau
                const liste = document.getElementById('liste').innerHTML = response.data.liste;
                // Ajout d'un event sur Bouton de suppression dans la fenêtre modale
                document.querySelectorAll('a.page-link').forEach(function(link){
                    link.addEventListener('click', onClickPage)
                })
            })
    });
});

// Construction de la fonction OnClickBtnMenu
function onClickPage(event){
    event.preventDefault();
    const url = this.href;

    console.log(url)
    axios
        .get(url)
        .then(response => {
            // rafraichissement du tableau
            const liste = document.getElementById('liste').innerHTML = response.data.liste;
            // Ajout d'un event sur Bouton de suppression dans la fenêtre modale
            document.querySelectorAll('a.page-link').forEach(function(link){
                link.addEventListener('click', onClickPage)
            })
        })
}

// Ajout d'un event sur le lien de pagination
document.querySelectorAll('a.page-link').forEach(function(link){
    link.addEventListener('click', onClickPage)
})


