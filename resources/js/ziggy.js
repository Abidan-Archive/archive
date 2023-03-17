const Ziggy = {
    url: 'http://localhost',
    port: null,
    defaults: {},
    routes: {
        'sanctum.csrf-cookie': {
            uri: 'sanctum/csrf-cookie',
            methods: ['GET', 'HEAD'],
        },
        'ignition.healthCheck': {
            uri: '_ignition/health-check',
            methods: ['GET', 'HEAD'],
        },
        'ignition.executeSolution': {
            uri: '_ignition/execute-solution',
            methods: ['POST'],
        },
        'ignition.updateConfig': {
            uri: '_ignition/update-config',
            methods: ['POST'],
        },
        home: { uri: '/', methods: ['GET', 'HEAD'] },
        search: { uri: 'search', methods: ['GET', 'HEAD'] },
        'event.index': { uri: 'event', methods: ['GET', 'HEAD'] },
        'event.create': { uri: 'event/create', methods: ['GET', 'HEAD'] },
        'event.store': { uri: 'event', methods: ['POST'] },
        'event.show': {
            uri: 'event/{event}',
            methods: ['GET', 'HEAD'],
            bindings: { event: 'id' },
        },
        'event.edit': {
            uri: 'event/{event}/edit',
            methods: ['GET', 'HEAD'],
            bindings: { event: 'id' },
        },
        'event.update': {
            uri: 'event/{event}',
            methods: ['PUT', 'PATCH'],
            bindings: { event: 'id' },
        },
        'event.destroy': {
            uri: 'event/{event}',
            methods: ['DELETE'],
            bindings: { event: 'id' },
        },
        'event.report.index': {
            uri: 'event/{event}/report',
            methods: ['GET', 'HEAD'],
        },
        'event.report.create': {
            uri: 'event/{event}/report/create',
            methods: ['GET', 'HEAD'],
        },
        'event.report.store': {
            uri: 'event/{event}/report',
            methods: ['POST'],
        },
        'report.show': {
            uri: 'report/{report}',
            methods: ['GET', 'HEAD'],
            bindings: { report: 'id' },
        },
        'report.edit': {
            uri: 'report/{report}/edit',
            methods: ['GET', 'HEAD'],
            bindings: { report: 'id' },
        },
        'report.update': {
            uri: 'report/{report}',
            methods: ['PUT', 'PATCH'],
            bindings: { report: 'id' },
        },
        'report.destroy': {
            uri: 'report/{report}',
            methods: ['DELETE'],
            bindings: { report: 'id' },
        },
        'tag.index': { uri: 'tag', methods: ['GET', 'HEAD'] },
        'tag.create': { uri: 'tag/create', methods: ['GET', 'HEAD'] },
        'tag.store': { uri: 'tag', methods: ['POST'] },
        'tag.show': {
            uri: 'tag/{tag}',
            methods: ['GET', 'HEAD'],
            bindings: { tag: 'id' },
        },
        'tag.edit': {
            uri: 'tag/{tag}/edit',
            methods: ['GET', 'HEAD'],
            bindings: { tag: 'id' },
        },
        'tag.update': {
            uri: 'tag/{tag}',
            methods: ['PUT', 'PATCH'],
            bindings: { tag: 'id' },
        },
        'tag.destroy': {
            uri: 'tag/{tag}',
            methods: ['DELETE'],
            bindings: { tag: 'id' },
        },
        register: { uri: 'register', methods: ['GET', 'HEAD'] },
        login: { uri: 'login', methods: ['GET', 'HEAD'] },
        'password.request': {
            uri: 'forgot-password',
            methods: ['GET', 'HEAD'],
        },
        'password.email': { uri: 'forgot-password', methods: ['POST'] },
        'password.reset': {
            uri: 'reset-password/{token}',
            methods: ['GET', 'HEAD'],
        },
        'password.update': { uri: 'reset-password', methods: ['POST'] },
        'verification.notice': {
            uri: 'verify-email',
            methods: ['GET', 'HEAD'],
        },
        'verification.verify': {
            uri: 'verify-email/{id}/{hash}',
            methods: ['GET', 'HEAD'],
        },
        'verification.send': {
            uri: 'email/verification-notification',
            methods: ['POST'],
        },
        'password.confirm': {
            uri: 'confirm-password',
            methods: ['GET', 'HEAD'],
        },
        logout: { uri: 'logout', methods: ['POST'] },
    },
};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
