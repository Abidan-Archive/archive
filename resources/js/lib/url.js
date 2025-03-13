// https://stackoverflow.com/a/5717133
export default function isValidUrl(value) {
    const pattern = new RegExp(
        '^(https?:\\/\\/)?' + // protocol
            '((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|' + // domain name
            '((\\d{1,3}\\.){3}\\d{1,3}))' + // OR ip (v4) address
            '(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' + // port and path
            '(\\?[;&a-z\\d%_.~+=-]*)?' + // query string
            '(\\#[-a-z\\d_]*)?$',
        'i'
    ); // fragment locator
    return !!pattern.test(value);
}

export function parseNestedParams(params) {
    return Object.keys(params).reduce((result, key) => {
        const parts = key.replace(/\]/g, '').split('[');
        parts
            .slice(0, -1)
            .reduce((acc, part) => (acc[part] = acc[part] || {}), result)[
            parts[parts.length - 1]
        ] = params[key];
        return result;
    }, {});
}
