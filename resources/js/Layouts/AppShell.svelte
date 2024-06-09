<div
    id="appShell"
    data-testid="app-shell"
    class="flex h-full w-full flex-col overflow-hidden">
    <header class="z-10 flex-none">
        <slot name="header" />
    </header>
    <div id="content-area" class="flex h-full w-full flex-auto overflow-hidden">
        <div
            id="page"
            class="flex flex-1 flex-col overflow-x-hidden"
            style:scrollbar-gutter="auto"
            on:scroll>
            <main id="page-content" class="flex-auto">
                <slot />
            </main>
            <footer id="page-footer" class="flex-none">
                <slot name="footer" />
            </footer>
        </div>
    </div>
</div>
