<script>
    import route from '@/Utils/route';
    import Sidebar from './components/Sidebar.svelte';

    const links = {
        'Create Event': route('event.create'),
        'Create Tag': route('home'),
        'Review Reports': route('home'),
    };
</script>

<svelte:head>
    <title>Admin Portal | Abidan Archive</title>
</svelte:head>

<div class="mt-8 flex flex-row gap-4">
    <Sidebar />
    <div class="flex-1 ">
        Other stuff
    </div>
</div>
