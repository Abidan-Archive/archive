/** @type {import("prettier").Config} */
export default {
    bracketSameLine: true,
    semi: true,
    singleQuote: true,
    svelteSortOrder: "options-scripts-markup-styles",
    tabWidth: 4,
    trailingComma: "es5",
    importOrder: ["^@/(.*)$", "^[./]"],
    importOrderSeparation: true,
    importOrderSortSpecifiers: true,
    plugins: [
        "@trivago/prettier-plugin-sort-imports",
        "prettier-plugin-svelte",
        "prettier-plugin-tailwindcss"
    ],
};
