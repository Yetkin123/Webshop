const sendButton = document.querySelector("#verzenden-button");
const bedrijfsnaam = document.querySelector("#bedrijfsnaam");
const voornaam = document.querySelector("#voornaam");
const achternaam = document.querySelector("#achternaam");
const email = document.querySelector("#email");
const telefoonnummer = document.querySelector("#telefoonnummer");
const vraag = document.querySelector("#vraag");
const modal = document.querySelector("#modal");
const closeModal = document.querySelector("#close-modal");

// Modal openen en velden leegmaken wanneer er op de verzenden knop geklikt wordt
sendButton.addEventListener("click", function() {
    modal.style.display = "flex";
    bedrijfsnaam.value = "";
    voornaam.value = "";
    achternaam.value = "";
    email.value = "";
    telefoonnummer.value = "";
    vraag.value = "";
})

// Modal sluiten wanneer er op Escape gedrukt wordt
window.addEventListener("keydown", function(e) {
    if (e.key === "Escape") {
        closeButtonModal();
    }
})

// Modal sluiten wanneer er op kruisje geklikt wordt
closeModal.addEventListener("click", function() {
    closeButtonModal();
})

// Functie om modal te sluiten. Past display van modal aan.
// Modal display: "none";
function closeButtonModal() {
    modal.style.display = "none";
}
