const modal = document.querySelector("#modal");
const images = document.querySelectorAll(".product-image");
const modalImage = document.querySelector("#modal-image");
const closeModal = document.querySelector("#close-modal");
const header = document.querySelector("#header");
const aside = document.querySelector("#aside")

// Modal met afbeelding openen wanneer er op de afbeelding geklikt wordt
for (const image of images) {
    image.addEventListener("click", function() {
        const imageName = this.src;
        modalImage.src = imageName;
        modal.style.display = "flex";
        header.style.display = "none";
        document.body.style.overflow = "hidden"; 
    })
}

// Modal met afbeelding sluiten wanneer er op Escape gedrukt wordt
window.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
        closeImageModal();
    }
})

// Modal met afbeelding sluiten wanneer er op kruisje geklikt wordt
closeModal.addEventListener("click", function() {
    closeImageModal();
})

// Functie om modal te sluiten. Past display van modal en header aan.
// Modal display: "none";
// Header display: "block";
function closeImageModal() {
    modal.style.display = "none";
    header.style.display = "block";
    document.body.style.overflow = "auto";
}

modal.addEventListener('click', function(e){
    if(e.target === modal) closeImageModal();
});