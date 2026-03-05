function closeSuccessPopup() {
    const popup = document.getElementById('successPopup');
    if (popup) {
        popup.style.opacity = '0';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 300);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.querySelector('.contact-form');
    if (contactForm && contactForm.dataset.success === 'true') {
        contactForm.reset();
        
        setTimeout(() => {
            closeSuccessPopup();
        }, 5000);
    }

    document.addEventListener('click', function(event) {
        const popup = document.getElementById('successPopup');
        if (popup && event.target == popup) {
            closeSuccessPopup();
        }
    });
});

