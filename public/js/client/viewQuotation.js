let URLROOT;

document.addEventListener('DOMContentLoaded', function() {
    URLROOT = document.querySelector('meta[name="urlroot"]').getAttribute('content');
});



function openPopup(popupId) {
    document.querySelector('.popup-overlay').style.display = 'block';
    document.getElementById(popupId).style.display = 'block';
}

function closePopup(popupId) {
    document.querySelector('.popup-overlay').style.display = 'none';
    document.getElementById(popupId).style.display = 'none';
}

// Update form submit handlers
document.getElementById('acceptForm').addEventListener('submit', function(e) {
    e.preventDefault();
    openPopup('acceptPopup');
});

document.getElementById('rejectForm').addEventListener('submit', function(e) {
    e.preventDefault();
    openPopup('rejectPopup');

});

function downloadQuotation() {
    // Get quotation ID from the URL instead of trying to find an element
    const urlParts = window.location.pathname.split('/');
    const quotationId = urlParts[urlParts.length - 1];
    
    if (quotationId) {
        window.location.href = URLROOT + '/client/downloadQuotation/' + quotationId;
    } else {
        console.error('Quotation ID not found');
    }

    
}

