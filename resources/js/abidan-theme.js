export const abidanTheme = {
    name: 'abidan',
    properties: {
        // // =~= Theme Properties =~=
        '--theme-font-family-base': `system-ui`,
        '--theme-font-family-heading': `system-ui`,
        '--theme-font-color-base': '0 0 0',
        '--theme-font-color-dark': '255 255 255',
        '--theme-rounded-base': '6px',
        '--theme-rounded-container': '12px',
        '--theme-border-base': '2px',
        // // =~= Theme On-X Colors =~=
        // '--on-primary': '255 255 255',
        // '--on-secondary': '0 0 0',
        // '--on-tertiary': '0 0 0',
        // '--on-success': '0 0 0',
        // '--on-warning': '0 0 0',
        // '--on-error': '255 255 255',
        // '--on-surface': '255 255 255',
        // // =~= Theme Colors  =~=
        // // primary | #00417b
        // '--color-primary-50': '217 227 235', // #d9e3eb
        // '--color-primary-100': '204 217 229', // #ccd9e5
        // '--color-primary-200': '191 208 222', // #bfd0de
        // '--color-primary-300': '153 179 202', // #99b3ca
        // '--color-primary-400': '77 122 163', // #4d7aa3
        // '--color-primary-500': '0 65 123', // #00417b
        // '--color-primary-600': '0 59 111', // #003b6f
        // '--color-primary-700': '0 49 92', // #00315c
        // '--color-primary-800': '0 39 74', // #00274a
        // '--color-primary-900': '0 32 60', // #00203c
        // // secondary | #8b5cf6
        // '--color-secondary-50': '238 231 254', // #eee7fe
        // '--color-secondary-100': '232 222 253', // #e8defd
        // '--color-secondary-200': '226 214 253', // #e2d6fd
        // '--color-secondary-300': '209 190 251', // #d1befb
        // '--color-secondary-400': '174 141 249', // #ae8df9
        // '--color-secondary-500': '139 92 246', // #8b5cf6
        // '--color-secondary-600': '125 83 221', // #7d53dd
        // '--color-secondary-700': '104 69 185', // #6845b9
        // '--color-secondary-800': '83 55 148', // #533794
        // '--color-secondary-900': '68 45 121', // #442d79
        // // tertiary | #22d3ee
        // '--color-tertiary-50': '222 248 252', // #def8fc
        // '--color-tertiary-100': '211 246 252', // #d3f6fc
        // '--color-tertiary-200': '200 244 251', // #c8f4fb
        // '--color-tertiary-300': '167 237 248', // #a7edf8
        // '--color-tertiary-400': '100 224 243', // #64e0f3
        // '--color-tertiary-500': '34 211 238', // #22d3ee
        // '--color-tertiary-600': '31 190 214', // #1fbed6
        // '--color-tertiary-700': '26 158 179', // #1a9eb3
        // '--color-tertiary-800': '20 127 143', // #147f8f
        // '--color-tertiary-900': '17 103 117', // #116775
        // // success | #58d336
        // '--color-success-50': '230 248 225', // #e6f8e1
        // '--color-success-100': '222 246 215', // #def6d7
        // '--color-success-200': '213 244 205', // #d5f4cd
        // '--color-success-300': '188 237 175', // #bcedaf
        // '--color-success-400': '138 224 114', // #8ae072
        // '--color-success-500': '88 211 54', // #58d336
        // '--color-success-600': '79 190 49', // #4fbe31
        // '--color-success-700': '66 158 41', // #429e29
        // '--color-success-800': '53 127 32', // #357f20
        // '--color-success-900': '43 103 26', // #2b671a
        // // warning | #ff772e
        // '--color-warning-50': '255 235 224', // #ffebe0
        // '--color-warning-100': '255 228 213', // #ffe4d5
        // '--color-warning-200': '255 221 203', // #ffddcb
        // '--color-warning-300': '255 201 171', // #ffc9ab
        // '--color-warning-400': '255 160 109', // #ffa06d
        // '--color-warning-500': '255 119 46', // #ff772e
        // '--color-warning-600': '230 107 41', // #e66b29
        // '--color-warning-700': '191 89 35', // #bf5923
        // '--color-warning-800': '153 71 28', // #99471c
        // '--color-warning-900': '125 58 23', // #7d3a17
        // // error | #d51a1a
        // '--color-error-50': '249 221 221', // #f9dddd
        // '--color-error-100': '247 209 209', // #f7d1d1
        // '--color-error-200': '245 198 198', // #f5c6c6
        // '--color-error-300': '238 163 163', // #eea3a3
        // '--color-error-400': '226 95 95', // #e25f5f
        // '--color-error-500': '213 26 26', // #d51a1a
        // '--color-error-600': '192 23 23', // #c01717
        // '--color-error-700': '160 20 20', // #a01414
        // '--color-error-800': '128 16 16', // #801010
        // '--color-error-900': '104 13 13', // #680d0d
        // // surface | #1b2735
        // '--color-surface-50': '221 223 225', // #dddfe1
        // '--color-surface-100': '209 212 215', // #d1d4d7
        // '--color-surface-200': '198 201 205', // #c6c9cd
        // '--color-surface-300': '164 169 174', // #a4a9ae
        // '--color-surface-400': '95 104 114', // #5f6872
        // '--color-surface-500': '27 39 53', // #1b2735
        // '--color-surface-600': '24 35 48', // #182330
        // '--color-surface-700': '20 29 40', // #141d28
        // '--color-surface-800': '16 23 32', // #101720
        // '--color-surface-900': '13 19 26', // #0d131a
        // =~= Theme Properties =~=
        // "--theme-font-family-base": `system-ui`,
        // "--theme-font-family-heading": `system-ui`,
        // "--theme-font-color-base": "0 0 0",
        // "--theme-font-color-dark": "255 255 255",
        // "--theme-rounded-base": "6px",
        // "--theme-rounded-container": "12px",
        // "--theme-border-base": "2px",
        // =~= Theme On-X Colors =~=
        '--on-primary': '255 255 255',
        '--on-secondary': '0 0 0',
        '--on-tertiary': '0 0 0',
        '--on-success': '0 0 0',
        '--on-warning': '0 0 0',
        '--on-error': '255 255 255',
        '--on-surface': '255 255 255',
        // =~= Theme Colors  =~=
        // primary | #0661b1
        '--color-primary-50': '218 231 243', // #dae7f3
        '--color-primary-100': '205 223 239', // #cddfef
        '--color-primary-200': '193 216 236', // #c1d8ec
        '--color-primary-300': '155 192 224', // #9bc0e0
        '--color-primary-400': '81 144 200', // #5190c8
        '--color-primary-500': '6 97 177', // #0661b1
        '--color-primary-600': '5 87 159', // #05579f
        '--color-primary-700': '5 73 133', // #054985
        '--color-primary-800': '4 58 106', // #043a6a
        '--color-primary-900': '3 48 87', // #033057
        // secondary | #8b5cf6
        '--color-secondary-50': '238 231 254', // #eee7fe
        '--color-secondary-100': '232 222 253', // #e8defd
        '--color-secondary-200': '226 214 253', // #e2d6fd
        '--color-secondary-300': '209 190 251', // #d1befb
        '--color-secondary-400': '174 141 249', // #ae8df9
        '--color-secondary-500': '139 92 246', // #8b5cf6
        '--color-secondary-600': '125 83 221', // #7d53dd
        '--color-secondary-700': '104 69 185', // #6845b9
        '--color-secondary-800': '83 55 148', // #533794
        '--color-secondary-900': '68 45 121', // #442d79
        // tertiary | #22d3ee
        '--color-tertiary-50': '222 248 252', // #def8fc
        '--color-tertiary-100': '211 246 252', // #d3f6fc
        '--color-tertiary-200': '200 244 251', // #c8f4fb
        '--color-tertiary-300': '167 237 248', // #a7edf8
        '--color-tertiary-400': '100 224 243', // #64e0f3
        '--color-tertiary-500': '34 211 238', // #22d3ee
        '--color-tertiary-600': '31 190 214', // #1fbed6
        '--color-tertiary-700': '26 158 179', // #1a9eb3
        '--color-tertiary-800': '20 127 143', // #147f8f
        '--color-tertiary-900': '17 103 117', // #116775
        // success | #58d336
        '--color-success-50': '230 248 225', // #e6f8e1
        '--color-success-100': '222 246 215', // #def6d7
        '--color-success-200': '213 244 205', // #d5f4cd
        '--color-success-300': '188 237 175', // #bcedaf
        '--color-success-400': '138 224 114', // #8ae072
        '--color-success-500': '88 211 54', // #58d336
        '--color-success-600': '79 190 49', // #4fbe31
        '--color-success-700': '66 158 41', // #429e29
        '--color-success-800': '53 127 32', // #357f20
        '--color-success-900': '43 103 26', // #2b671a
        // warning | #ff772e
        '--color-warning-50': '255 235 224', // #ffebe0
        '--color-warning-100': '255 228 213', // #ffe4d5
        '--color-warning-200': '255 221 203', // #ffddcb
        '--color-warning-300': '255 201 171', // #ffc9ab
        '--color-warning-400': '255 160 109', // #ffa06d
        '--color-warning-500': '255 119 46', // #ff772e
        '--color-warning-600': '230 107 41', // #e66b29
        '--color-warning-700': '191 89 35', // #bf5923
        '--color-warning-800': '153 71 28', // #99471c
        '--color-warning-900': '125 58 23', // #7d3a17
        // error | #d51a1a
        '--color-error-50': '249 221 221', // #f9dddd
        '--color-error-100': '247 209 209', // #f7d1d1
        '--color-error-200': '245 198 198', // #f5c6c6
        '--color-error-300': '238 163 163', // #eea3a3
        '--color-error-400': '226 95 95', // #e25f5f
        '--color-error-500': '213 26 26', // #d51a1a
        '--color-error-600': '192 23 23', // #c01717
        '--color-error-700': '160 20 20', // #a01414
        '--color-error-800': '128 16 16', // #801010
        '--color-error-900': '104 13 13', // #680d0d
        // surface | #1b2735
        '--color-surface-50': '221 223 225', // #dddfe1
        '--color-surface-100': '209 212 215', // #d1d4d7
        '--color-surface-200': '198 201 205', // #c6c9cd
        '--color-surface-300': '164 169 174', // #a4a9ae
        '--color-surface-400': '95 104 114', // #5f6872
        '--color-surface-500': '27 39 53', // #1b2735
        '--color-surface-600': '24 35 48', // #182330
        '--color-surface-700': '20 29 40', // #141d28
        '--color-surface-800': '16 23 32', // #101720
        '--color-surface-900': '13 19 26', // #0d131a
    },
};
