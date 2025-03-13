<script>
    import route from '@/lib/route';
    import Page from '@/Components/Page.svelte';
    import Report from '@/Components/Report.svelte';

    let { report, auth } = $props();
    let desc = $derived(report.dialogues[0]?.line || 'description');
    const edit =
        auth.user?.permissions.includes('edit_report') &&
        route('report.edit', report);
</script>

<svelte:head>
    <title>Report {report.id} | Abidan Archive</title>
    <meta property="og:title" content={`#${report.id} | Abidan Archive`} />
    <meta property="og:type" content="article" />
    <meta property="og:url" content={report.permalink} />
    <meta property="og:site_name" content="Abidan Archive" />
    <meta property="og:description" content={desc} />
    <meta name="twitter:card" content="summary" />
    <meta name="twitter:title" content={`#${report.id} | Abidan Archive`} />
    <meta name="twitter:description" content={desc} />
</svelte:head>

<Page header={`Report #${report.id}`} {edit}>
    <Report {report} />
</Page>
