const modale1 = document.querySelector("#modalecatchAFire")
const modale2 = document.querySelector("#modaleburnin")
const modale3 = document.querySelector("#modalenattyFread")
const modale4 = document.querySelector("#modalerastamanVibration")
const modale5 = document.querySelector("#modaleexodus")
const modale6 = document.querySelector("#modalekaya")
const modale7 = document.querySelector("#modalesurvival")
const modale8 = document.querySelector("#modaleuprising")
const close = document.querySelector(".close")
const img1 = document.querySelector(".discocatchAFire svg")
const img2 = document.querySelector(".discoburnin svg")
const img3 = document.querySelector(".disconattyFread svg")
const img4 = document.querySelector(".discorastamanVibration svg")
const img5 = document.querySelector(".discoexodus svg")
const img6 = document.querySelector(".discokaya svg")
const img7 = document.querySelector(".discosurvival svg")
const img8 = document.querySelector(".discouprising svg")

const openModal = (image, modale) => {
    image.addEventListener("click", function (e) {
            e.preventDefault();
            modale.querySelector(".modal-content");
            modale.classList.add("show");
        })
};

const closeModal = (modale) => {
    modale.addEventListener("click", function () {
        modale.classList.remove("show")
    })
};

close.addEventListener("click", function () {
    for (let i = 1; i > 8; i++) {
        modale[i].classList.remove("show")
    }
})


openModal(img1, modale1);
openModal(img2, modale2);
openModal(img3, modale3);
openModal(img4, modale4);
openModal(img5, modale5);
openModal(img6, modale6);
openModal(img7, modale7);
openModal(img8, modale8);

closeModal(modale1);
closeModal(modale2);
closeModal(modale3);
closeModal(modale4);
closeModal(modale5);
closeModal(modale6);
closeModal(modale7);
closeModal(modale8);
