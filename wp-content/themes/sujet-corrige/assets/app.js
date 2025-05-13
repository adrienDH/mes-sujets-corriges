document.addEventListener("DOMContentLoaded", function () {

    // Pour afficher le message d'erreur dans la modal de login
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has("login") && urlParams.get("login") === "failed") {
        var myModal = new bootstrap.Modal(document.getElementById('modal_connection'));
        myModal.show();
    }

    document.getElementById('submit_contact').addEventListener('click', function() {
        const contactWrapper = document.querySelector('.contact-form-wrapper');
        const ajaxUrl = contactWrapper.dataset.ajaxurl;
        const data = {
            action: contactWrapper.dataset.action,
            nonce: contactWrapper.dataset.nonce,
            email: contactWrapper.querySelector('#email').value,
            message: contactWrapper.querySelector('#message').value,
        };

        fetch(ajaxUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'Cache-Control': 'no-cache',
            },
            body: new URLSearchParams(data),
        })
            .then((response) => response.json())
            .then((response) => {
                if(response.success) {
                    contactWrapper.querySelector('.success').classList.remove('d-none')
                    contactWrapper.querySelector('.error').classList.add('d-none')
                    contactWrapper.querySelectorAll('.form-fields').forEach(field => {
                        field.classList.add('d-none');
                    });
                } else {
                    contactWrapper.querySelector('.error').classList.remove('d-none')
                }
            });
    });
});

