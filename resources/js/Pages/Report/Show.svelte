<script>
    import Report from '@components/Report.svelte';

    export let report;
</script>

<svelte:head>
    <meta property="og:title" content={`#${report.id} | Abidan Archive`}>
    <meta property="og:type" content="article">
    <meta property="og:url"  content={report.permalink}>
    <meta property="og:site_name" content="Abidan Archive">
    <meta property="og:description" content={report.dialogues[0].line}>
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content={`#${report.id} | Abidan Archive`}>
    <meta name="twitter:description" content={report.dialogues[0].line}>
</svelte:head>

<section class="container mx-auto p-5">
    <Report {report} />
</section>
