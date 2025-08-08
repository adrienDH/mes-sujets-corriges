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

// Exécuter la fonction une fois le DOM chargé
document.addEventListener('DOMContentLoaded', function toggleCardsVisibility() {
    const cardWrappers = document.querySelectorAll('.card-wrapper');
    
    cardWrappers.forEach(card => {
        const withCorrections = card.getAttribute('data-with-corrections');
        
        if (withCorrections == 1) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
});

function initCardFilterByCorrections() {    
    const checkbox = document.querySelector('input[name="with-correction"]');
    function filterCards() {
        const isChecked = checkbox.checked;
        const cardWrappers = document.querySelectorAll('.card-wrapper');        

        cardWrappers.forEach(card => {
            const withCorrections = card.getAttribute('data-with-corrections');
            const hasCorrections = withCorrections == 1;
            
            if (isChecked) {
                // Si checkbox cochée : afficher seulement les cards AVEC corrections
                if (hasCorrections) {
                    card.style.setProperty('display', 'flex', 'important');
                } else {
                    card.style.setProperty('display', 'none', 'important');
                }
            } else {
                // Si checkbox décochée : afficher toutes les cards
                card.style.setProperty('display', 'flex', 'important');
            }
        });
    }
    
    // Écouter les changements sur la checkbox
    checkbox.addEventListener('change', filterCards);
    
    // Appliquer le filtre initial (si la checkbox est déjà cochée au chargement)
    filterCards();
}

// Initialiser une fois le DOM chargé
document.addEventListener('DOMContentLoaded', initCardFilterByCorrections);