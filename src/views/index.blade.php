<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Swagger UI</title>
    <link rel="icon" type="image/png" href="/vendor/swaggervel/images/favicon-32x32.png" sizes="32x32" />
    <link rel="icon" type="image/png" href="/vendor/swaggervel/images/favicon-16x16.png" sizes="16x16" />
    <link href='/vendor/swaggervel/css/typography.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
    <link href='/vendor/swaggervel/css/print.css' media='print' rel='stylesheet' type='text/css'/>
    <script src='/vendor/swaggervel/lib/jquery-1.8.0.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jquery.slideto.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jquery.wiggle.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/handlebars-2.0.0.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/js-yaml.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/lodash.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/backbone-min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/swagger-ui.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/highlight.9.1.0.pack.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/highlight.9.1.0.pack_extended.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/jsoneditor.min.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/marked.js' type='text/javascript'></script>
    <script src='/vendor/swaggervel/lib/swagger-oauth.js' type='text/javascript'></script>

    <script type="text/javascript">
        $(function () {
            var url = window.location.search.match(/url=([^&]+)/);
            if (url && url.length > 1) {
                url = decodeURIComponent(url[1]);
            } else {
                url = "{!! $urlToDocs !!}";
            }

            hljs.configure({
                highlightSizeThreshold: 5000
            });

            // Pre load translate...
            if(window.SwaggerTranslator) {
                window.SwaggerTranslator.translate();
            }
            window.swaggerUi = new SwaggerUi({
                url: url,
                dom_id: "swagger-ui-container",
                supportedSubmitMethods: ['get', 'post', 'put', 'delete', 'patch'],
                onComplete: function(swaggerApi, swaggerUi){
                    @if(isset($requestHeaders))
                        @foreach($requestHeaders as $requestKey => $requestValue)
                        window.authorizations.add("{!!$requestKey!!}", new ApiKeyAuthorization("{!!$requestKey!!}", "{!!$requestValue!!}", "header"));
                        @endforeach
                    @endif
                    if(typeof initOAuth == "function") {
                        initOAuth({
                            clientId: "{!! $clientId !!}"||"my-client-id",
                            clientSecret: "{!! $clientSecret !!}"||"_",
                            realm: "{!! $realm !!}"||"_",
                            appName: "{!! $appName !!}"||"_",
                            scopeSeparator: ",",
                            additionalQueryStringParams: {}
                        });

                        window.oAuthRedirectUrl = "{{ url('vendor/swaggervel/o2c.html') }}";
                        $('#clientId').html("{!! $clientId !!}"||"my-client-id");
                        $('#redirectUrl').html(window.oAuthRedirectUrl);
                    }

                    if(window.SwaggerTranslator) {
                        window.SwaggerTranslator.translate();
                    }
                },
                onFailure: function(data) {
                    log("Unable to Load SwaggerUI");
                },
                docExpansion: "none",
                jsonEditor: true,
                defaultModelRendering: 'schema',
                showRequestHeaders: false
            });

            window.swaggerUi.load();

            function log() {
                if ('console' in window) {
                    console.log.apply(console, arguments);
                }
            }
        });
    </script>
</head>

<body class="swagger-section">
<div id='header'>
    <div class="swagger-ui-wrap">
        <a id="logo" href="http://swagger.io"><img class="logo__img" alt="swagger" height="30" width="30" src="vendor/swaggervel/images/logo_small.png" /><span class="logo__title">swagger</span></a>
        <form id='api_selector'>
            <div class='input'><input placeholder="http://example.com/api" id="input_baseUrl" name="baseUrl" type="text"/></div>
            <div id='auth_container'></div>
            <div class='input'><a id="explore" class="header__btn" href="#" data-sw-translate>Explore</a></div>
        </form>
    </div>
</div>

<div id="message-bar" class="swagger-ui-wrap" data-sw-translate>&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
