export default class Oddment {
    items;
    oddment;
    constructor(items = {}) {
        this.items = items;
        this.oddment = this.calculateOddment();
    }

    calculateOddment() {
        let total = 0;
        Object.values(this.items).forEach((v) => (total += v));
        return total;
    }

    pick() {
        const n = Math.floor(Math.random() * (this.oddment + 1)); // 0 - oddment
        let total = 0;
        for (const [key, value] of Object.entries(this.items)) {
            total += value;
            if (total >= n) return key;
        }
        const keys = Object.keys(this.items);
        return keys[keys.length - 1];
    }
}
