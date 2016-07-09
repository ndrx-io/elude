'use strict';

angular.module('app.config', [])

    .constant('appname', 'Myo2')

    .constant('version', 'v0.1.0')

    .constant('appdir', 'assets/app')

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

    .config(function($urlRouterProvider, $locationProvider) {

        // For any unmatched url, redirect to /home
        $urlRouterProvider.otherwise('/home');
        //states are configured in each controller..

        $locationProvider.html5Mode(true);
    });
