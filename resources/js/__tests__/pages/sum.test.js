function sum(a, b) {
    return a + b;
}

it('sums a + b', () => {
    expect(sum(1, 2)).toBe(3);
})
