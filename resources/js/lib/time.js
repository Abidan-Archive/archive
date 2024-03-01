export function secondsToDuration(value) {
    const secs = `${parseFloat(`${value % 60}`, 10).toFixed(2)}`.padStart(
        2,
        '0'
    );
    const mins = parseInt(`${(value / 60) % 60}`, 10);
    const hours = parseInt(`${(value / 3600) % 60}`, 10);

    return (
        hours ? [hours, `${mins}`.padStart(2, '0'), secs] : [mins, secs]
    ).join(':');
}

export function durationToSeconds(value) {
    const parts = value.split(':').map(parseFloat).reverse();
    let sum = 0;
    for (let i = 0, n = parts.length; i < n; i++) {
        sum += parts[i] * Math.pow(60, i);
    }
    return sum;
}

export const formatToInputDateString = (datestamp) => {
    const d = new Date(datestamp);
    return [d.getFullYear(), d.getMonth(), d.getDate()]
        .map((u, i) => u.toString().padStart(!i ? 4 : 2, '0'))
        .join('-');
};
