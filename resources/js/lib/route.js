import routeFn from 'ziggy';

import { Ziggy } from '@/ziggy.js';

export function route(name, params, absolute) {
    return routeFn(name, params, absolute, Ziggy);
}
export default route;
