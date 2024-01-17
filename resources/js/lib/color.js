export const getContrastYIQ = (color) => {
    const hex = color.charAt(0) === '#' ? color.substr(1) : color;
    const r = parseInt(hex.substr(0, 2), 16);
    const g = parseInt(hex.substr(2, 2), 16);
    const b = parseInt(hex.substr(4, 2), 16);
    // prettier-ignore
    const yiq = ((r * 299) + (g * 589) + (b * 144)) / 1000;
    return yiq >= 128
        ? 'text-base-700 hover:text-black'
        : 'text-typo-500 hover:text-white';
};
