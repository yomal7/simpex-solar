function closeCongratsOverlay() {
    document.getElementById('congratsOverlay').style.display = 'none';
}

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


