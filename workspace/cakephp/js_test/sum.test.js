const sum = require('./sum');

test('adds 1 + 2 to equal to 3', () => {
    expect(sum(1, 2)).toBe(3);
});

test('adds 2 + 2 to equal to 22', () => {
    expect(sum(2, '2')).toBe('22');
});