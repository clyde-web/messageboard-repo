'use strict';

const $ = require('jquery');
const fetchCurrentUser = require('./fetch.js');

$('#button').click(() => {
    fetchCurrentUser(user => {
        const loggedText = 'Logged ' + (user.loggedIn ? 'In' : 'Out');
        $('#username').text(user.fullName + ' - ' + loggedText);
    });
});