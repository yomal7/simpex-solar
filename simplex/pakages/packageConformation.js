let popup= document.getElementById("popup")
let overlay = document.getElementById("overlay");

function openPopup(){
    popup.classList.add("open-popup")
    overlay.style.visibility = 'visible';
    overlay.style.opacity = '1';
}

function closePopup(){
    popup.classList.remove("open-popup")
    overlay.style.visibility = 'hidden';
    overlay.style.opacity = '0';
}