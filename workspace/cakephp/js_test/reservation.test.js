'use strict';

const $ = require('jquery');
const { openReservation, showPrompt, showLogin, redirectToRegister } = require('./reservation');
jest.mock('./user');

beforeEach(() => {
    document.body.innerHTML = `<div id="reservations">
    <a href="javascript:void(0);" class="open-reservation" data-id="1">
        10 - 21 - 2024
    </a>
    </div>
    <div class="modal" id="modal_prompt">
        <button type="button" class="btn btn-login">Login</button>
        <button type="button" class="btn btn-register">Register</button>
    </div>
    <div class="modal" id="modal_login">
        <h1>Login Modal</h1>
    </div>
    <div class="modal" id="reservation_details">
        <h1>Reservation Details</h1>
    </div>`;
})

test('should show login modal prompt if not logged in and reservation date is clicked', () => {
    const { isLoggedIn } = require('./user');
    isLoggedIn.mockReturnValue(false);

    $('.open-reservation').on('click', function (e) {
        e.preventDefault();
        openReservation(isLoggedIn())
    });
    $('.open-reservation').click();

    expect(isLoggedIn).toHaveBeenCalled();
    expect($('#modal_prompt').attr('class')).toBe('modal show');
})

test('should show reservation details if logged in and reservation date is clicked', () => {
    const { isLoggedIn } = require('./user');
    isLoggedIn.mockReturnValue(true);

    $('.open-reservation').on('click', function (e) {
        e.preventDefault();
        openReservation(isLoggedIn())
    });
    $('.open-reservation').click();

    expect(isLoggedIn).toHaveBeenCalled();
    expect($('#modal_prompt').attr('class')).toBe('modal');
    expect($('#reservation_details').attr('class')).toBe('modal show');
});

test('should show login modal when login button is clicked', () => {
    const { isLoggedIn } = require('./user');
    isLoggedIn.mockReturnValue(false);

    $('.open-reservation').on('click', function (e) {
        e.preventDefault();
        openReservation(isLoggedIn())
    });
    $('.open-reservation').click();

    $('.btn-login').on('click', function () {
        showLogin();
    })

    $('.btn-login').click();

    expect(isLoggedIn).toHaveBeenCalled();
    expect($('#modal_prompt').attr('class')).toBe('modal');
    expect($('#modal_login').attr('class')).toBe('modal show');
});

test('should redirect to registration page when registration button is clicked', () => {
    const { isLoggedIn } = require('./user');
    isLoggedIn.mockReturnValue(false);

    $('.open-reservation').on('click', function (e) {
        e.preventDefault();
        openReservation(isLoggedIn())
    });
    $('.open-reservation').click();

    $('.btn-register').on('click', function () {
        redirectToRegister();
    })

    $('.btn-register').click();

    expect(isLoggedIn).toHaveBeenCalled();
    expect($('#modal_prompt').attr('class')).toBe('modal');
    expect(window.location.href).toBe('http://localhost/');
});