import assert from 'node:assert/strict';
import { spawn } from 'node:child_process';
import { once } from 'node:events';
import { setTimeout as delay } from 'node:timers/promises';

const port = 13971;
const server = spawn(process.execPath, ['bootstrap/ssr/ssr.js'], {
    env: { ...process.env, SSR_PORT: String(port) },
    stdio: ['ignore', 'pipe', 'pipe'],
});
let output = '';
server.stdout.on('data', (chunk) => (output += chunk));
server.stderr.on('data', (chunk) => (output += chunk));

try {
    for (let attempt = 0; attempt < 100; attempt++) {
        if (server.exitCode !== null) {
            await delay(50);
            assert.fail(output.split('\n').filter((line) => line.length < 1000).join('\n'));
        }
        if (output.includes('Inertia SSR server started.')) break;
        await delay(50);
    }
    assert.match(output, /Inertia SSR server started\./);
    for (const [component, props, expected] of [
        ['Auth/Login', {}, 'Log in'],
        ['Auth/Register', {}, 'Register'],
        ['Home', { quote: { quote: 'Test quotation', author: 'Test author' }, events: [], mostLiked: [], contributors: [] }, 'Abidan'],
        ['Report/Create', { events: [], tags: [] }, 'Report'],
    ]) {
        const response = await fetch(`http://127.0.0.1:${port}/render`, {
            method: 'POST',
            body: JSON.stringify({
                component,
                props: { auth: { user: null }, errors: {}, flash: null, ...props },
                url: '/',
                version: 'smoke',
                clearHistory: false,
                encryptHistory: false,
            }),
        });
        const text = await response.text();
        assert.ok(text, `${component}: ${output}`);
        const page = JSON.parse(text);
        assert.match(page.body, new RegExp(expected), `${component}: ${output}`);
        assert.ok(Array.isArray(page.head));
        console.log(`SSR rendered ${component}`);
    }
} finally {
    if (server.exitCode === null && server.signalCode === null) {
        const exited = once(server, 'exit');
        server.kill();
        await exited;
    }
}
