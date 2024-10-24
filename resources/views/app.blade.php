@includeWhen(App::isProduction(), 'gnome')
<!DOCTYPE html>
<html class="dark">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <script src="https://www.google.com/recaptcha/api.js?render={{config('services.recaptcha_v3.sitekey')}}"></script>
    <script>
        window.grecaptcha_sitekey = "{{config('services.recaptcha_v3.sitekey')}}";
    </script>
    @vite('resources/js/app.js')
    @inertiaHead
  </head>
  <body data-theme="abidan">
    <div style="display: contents" class="h-full overflow-hidden">
        @inertia
    </div>
  </body>
</html>
