<script>
    import AdminSidebar from './components/AdminSidebar.svelte';
    import Page from '@/components/Page.svelte';
</script>

<svelte:head>
    <title>Admin Portal | Abidan Archive</title>
</svelte:head>

<div class="container mt-8 flex h-full flex-row gap-4 px-4">
    <AdminSidebar />
    <Page header="Admin Overview" class="flex-1">Other stuff</Page>
</div>
