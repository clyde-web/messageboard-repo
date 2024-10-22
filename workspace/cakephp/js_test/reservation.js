const $ = require('jquery');

function openReservation(auth) {
    if (auth) {
        showDetails();
    } else {
        showPrompt();
    }
}

function showDetails() {
    $('#reservation_details').addClass('show');
}

function showPrompt() {
    $('#modal_prompt').addClass('show');
}

function showLogin() {
    $('#modal_prompt').removeClass('show');
    $('#modal_login').addClass('show');
}

function redirectToRegister() {
    $('#modal_prompt').removeClass('show');
    window.location.assign = '/register';
}

module.exports = {
    openReservation,
    showDetails,
    showPrompt,
    showLogin,
    redirectToRegister
}