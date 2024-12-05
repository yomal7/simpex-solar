let popup = document.getElementById("popup");
let overlay = document.getElementById("overlay");

function openPopup(e){
    if(e) e.preventDefault();
    popup.classList.add("open-popup");
    if(overlay) {
        overlay.style.visibility = 'visible';
        overlay.style.opacity = '1';
    }
}

function closePopup(){
    popup.classList.remove("open-popup");
    if(overlay) {
        overlay.style.visibility = 'hidden';
        overlay.style.opacity = '0';
    }
    window.location.href = '<?php echo URLROOT; ?>/users/profile';
}