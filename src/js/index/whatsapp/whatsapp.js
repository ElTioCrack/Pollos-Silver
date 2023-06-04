

const $form = document.querySelector('#contact-form');
const buttonSubmit = document.querySelector('#submit');
const urlDesktop = 'https://web.whatsapp.com/';
const urlMobile = 'whatsapp://';
const phone = '76265586';


$form.addEventListener('submit', (event) => {
    event.preventDefault()
    buttonSubmit.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i>'
    buttonSubmit.disabled = true

    setTimeout(() => {
        let name = document.querySelector('#form_name').value
        let problema = document.querySelector('#problema').value
        let email = document.querySelector('#email').value
        let message = 'send?phone=' + phone + '&text=*" Bienvenid@ a pollos frito Silver!, estamos para servirte. Indiquenos cual es su problema.*%0ASu nombre es: %0A' + name + '%0A*Su email: *%0A' + email + '%0A*¿Cual es su problema?:  %0A' + problema  


        if (isMobile()) {
            window.open(urlMobile + message, '_blank')
        } else {
            window.open(urlDesktop + message, '_blank')
        }

        buttonSubmit.innerHTML = '<i class="fab fa-whatsapp"></i> Enviar WhatsApp'
        buttonSubmit.disabled = false

    }, 4000);

});
