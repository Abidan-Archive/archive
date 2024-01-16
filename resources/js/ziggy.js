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
        about: { uri: 'about', methods: ['GET', 'HEAD'] },
        search: { uri: 'search', methods: ['GET', 'HEAD'] },
        handleRedirect: { uri: 'redirect', methods: ['GET', 'HEAD'] },
        'event.index': { uri: 'event', methods: ['GET', 'HEAD'] },
        'event.create': { uri: 'event/create', methods: ['GET', 'HEAD'] },
        'event.store': { uri: 'event', methods: ['POST'] },
        'event.show': {
            uri: 'event/{event}',
            methods: ['GET', 'HEAD'],
            parameters: ['event'],
            bindings: { event: 'id' },
        },
        'event.edit': {
            uri: 'event/{event}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['event'],
            bindings: { event: 'id' },
        },
        'event.update': {
            uri: 'event/{event}',
            methods: ['PUT', 'PATCH'],
            parameters: ['event'],
            bindings: { event: 'id' },
        },
        'event.destroy': {
            uri: 'event/{event}',
            methods: ['DELETE'],
            parameters: ['event'],
            bindings: { event: 'id' },
        },
        'event.report.index': {
            uri: 'event/{event}/report',
            methods: ['GET', 'HEAD'],
            parameters: ['event'],
        },
        'event.report.create': {
            uri: 'event/{event}/report/create',
            methods: ['GET', 'HEAD'],
            parameters: ['event'],
            bindings: { event: 'id' },
        },
        'event.report.store': {
            uri: 'event/{event}/report',
            methods: ['POST'],
            parameters: ['event'],
        },
        'report.show': {
            uri: 'report/{report}',
            methods: ['GET', 'HEAD'],
            parameters: ['report'],
            bindings: { report: 'id' },
        },
        'report.edit': {
            uri: 'report/{report}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['report'],
            bindings: { report: 'id' },
        },
        'report.update': {
            uri: 'report/{report}',
            methods: ['PUT', 'PATCH'],
            parameters: ['report'],
            bindings: { report: 'id' },
        },
        'report.destroy': {
            uri: 'report/{report}',
            methods: ['DELETE'],
            parameters: ['report'],
            bindings: { report: 'id' },
        },
        'event.source.stub.index': {
            uri: 'event/{event}/source/{source}/stub',
            methods: ['GET', 'HEAD'],
            parameters: ['event', 'source'],
            bindings: { event: 'id', source: 'id' },
        },
        'event.source.stub.create': {
            uri: 'event/{event}/source/{source}/stub/create',
            methods: ['GET', 'HEAD'],
            parameters: ['event', 'source'],
            bindings: { event: 'id', source: 'id' },
        },
        'event.source.stub.store': {
            uri: 'event/{event}/source/{source}/stub',
            methods: ['POST'],
            parameters: ['event', 'source'],
        },
        'event.source.stub.show': {
            uri: 'event/{event}/source/{source}/stub/{stub}',
            methods: ['GET', 'HEAD'],
            parameters: ['event', 'source', 'stub'],
        },
        'event.source.stub.edit': {
            uri: 'event/{event}/source/{source}/stub/{stub}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['event', 'source', 'stub'],
        },
        'event.source.stub.update': {
            uri: 'event/{event}/source/{source}/stub/{stub}',
            methods: ['PUT', 'PATCH'],
            parameters: ['event', 'source', 'stub'],
            bindings: { stub: 'id' },
        },
        'event.source.stub.destroy': {
            uri: 'event/{event}/source/{source}/stub/{stub}',
            methods: ['DELETE'],
            parameters: ['event', 'source', 'stub'],
            bindings: { stub: 'id' },
        },
        'event.source.show': {
            uri: 'event/{event}/source/{source}',
            methods: ['GET', 'HEAD'],
            parameters: ['event', 'source'],
            bindings: { event: 'id', source: 'id' },
        },
        'event.source.update': {
            uri: 'event/{event}/source/{source}',
            methods: ['PUT'],
            parameters: ['event', 'source'],
            bindings: { event: 'id', source: 'id' },
        },
        'event.source.destroy': {
            uri: 'event/{event}/source/{source}',
            methods: ['DELETE'],
            parameters: ['event', 'source'],
            bindings: { event: 'id', source: 'id' },
        },
        'stubs.all': { uri: 'stubs', methods: ['GET', 'HEAD'] },
        'tag.index': { uri: 'tag', methods: ['GET', 'HEAD'] },
        'tag.create': { uri: 'tag/create', methods: ['GET', 'HEAD'] },
        'tag.store': { uri: 'tag', methods: ['POST'] },
        'tag.show': {
            uri: 'tag/{tag}',
            methods: ['GET', 'HEAD'],
            parameters: ['tag'],
            bindings: { tag: 'id' },
        },
        'tag.edit': {
            uri: 'tag/{tag}/edit',
            methods: ['GET', 'HEAD'],
            parameters: ['tag'],
            bindings: { tag: 'id' },
        },
        'tag.update': {
            uri: 'tag/{tag}',
            methods: ['PUT', 'PATCH'],
            parameters: ['tag'],
            bindings: { tag: 'id' },
        },
        'tag.destroy': {
            uri: 'tag/{tag}',
            methods: ['DELETE'],
            parameters: ['tag'],
            bindings: { tag: 'id' },
        },
        like: { uri: 'like', methods: ['POST'] },
        unlike: { uri: 'like', methods: ['DELETE'] },
        'admin.index': { uri: 'admin', methods: ['GET', 'HEAD'] },
        'admin.assume': {
            uri: 'admin/assume/{user}',
            methods: ['POST'],
            parameters: ['user'],
            bindings: { user: 'id' },
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
            parameters: ['token'],
        },
        'password.update': { uri: 'reset-password', methods: ['POST'] },
        'verification.notice': {
            uri: 'verify-email',
            methods: ['GET', 'HEAD'],
        },
        'verification.verify': {
            uri: 'verify-email/{id}/{hash}',
            methods: ['GET', 'HEAD'],
            parameters: ['id', 'hash'],
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
