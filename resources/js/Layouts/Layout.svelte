<script>
    import Navigation from "@components/Navigation.svelte";
</script>

<div class="font-sans antialiased bg-white dark:bg-zinc-900 dark:text-white">
    <div class="min-h-screen">
        <Navigation />

        <main class="mt-16 h-screen">
            <slot />
        </main>
    </div>
</div>
