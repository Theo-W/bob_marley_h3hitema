window.onload = () => {
    const modale = document.querySelector("#modal-image")
    const close = document.querySelector(".close")
    const links = document.querySelectorAll(".galerie div a")

    //on ajout l'ecouteur clique sur les liens
    for(let link of links) {
        link.addEventListener("click", function(e){
            e.preventDefault()
            const image = modale.querySelector(".modal-content-image img")
            image.src = this.href
            modale.classList.add("show")
        })
    }

    close.addEventListener("click", function() {
        modale.classList.remove("show")
    })

    modale.addEventListener("click", function(){
        modale.classList.remove("show")
    })
}