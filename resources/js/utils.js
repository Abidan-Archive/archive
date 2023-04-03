export const formatToInputDateString = (datestamp) => {
    const d = new Date(datestamp);
    return [d.getFullYear(), d.getMonth(), d.getDate()]
        .map((u, i) => u.toString().padStart(!i ? 4 : 2, '0'))
        .join('-');
};
