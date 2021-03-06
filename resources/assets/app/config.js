'use strict';

angular.module('app.config', [
        'log.ex.uo'
    ])

    .constant('appname', 'Myo2')

    .constant('version', 'v0.1.0')

    // build process put template in a separate folder
    .constant('appdir', 'assets/template')

    //apiConfig is mandatory to use "Api" service of Myo
    .constant('apiConfig', {
        'url': '/api/v1', //you can set the whole URL here : e.g http://example.com/api/v1
        'token': {
            //read the auth token from the html (generated by the PHP part of Myo2)
            // this is why we have to include jquery ...
            'type': $('meta[name="token_type"]').prop('content'), //this should be "Bearer" if you use Myo as a WS
            'access': $('meta[name="access_token"]').prop('content'),
            'refresh': $('meta[name="refresh_token"]').prop('content')
        },
        'client': {
            'id': 'versusmind',
            'secret': 'versusmind'
        }
    })

    .config(function($urlRouterProvider, $locationProvider, logExProvider) {

        // For any unmatched url, redirect to /home
        $urlRouterProvider.otherwise('/home');
        //states are configured in each controller..

        $locationProvider.html5Mode(true);

        // Log-ex configurations
        // no output in prod mode
        logExProvider.enableLogging($('meta[name="environment"]').prop('content') !== 'production');
        logExProvider.overrideLogMethodColors({
            info: 'color:#0080FF',
            debug: 'color:#31B404',
            error: 'color:red'
        });

        // enhance log output
        logExProvider.overrideLogPrefix(function (className) {
            var $injector = angular.injector(['ng']),
                $filter = $injector.get('$filter'),
                separator = " >> ",
                format = "HH:mm:ss",
                now = $filter('date')(new Date(), format);

            return "" + now + (!angular.isString(className) ? "" : "::" + className) + separator;
        });
    });