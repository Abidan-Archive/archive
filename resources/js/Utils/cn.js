import { clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';

export default function (...inputs) {
    return twMerge(clsx(inputs));
}
