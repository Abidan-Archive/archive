import routeFn from 'ziggy';
import { Ziggy } from '@/ziggy.js';

export default function (name, params, absolute) {
    return routeFn(name, params, absolute, Ziggy);
}
