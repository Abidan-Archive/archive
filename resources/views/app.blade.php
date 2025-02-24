<!DOCTYPE html>
<html class="dark" lang="en-US">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <script>
        window.turnstile_sitekey = "{{config('services.turnstile.sitekey')}}";
    </script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @include('favicons')
    @vite('resources/js/app.js')
    @inertiaHead
  </head>
  {{-- Set background twice, one for ref, style is there so body is dark while app boots when ssr down --}}
  <body data-theme="abidan" class="bg-surface-900" style="background-color: rgb(13, 19, 26);">
    <div style="display: contents" class="h-full overflow-hidden">
        @inertia
    </div>
  </body>
</html>
