<?php

App::get('router')->attach('', array(
    'values' => array(
        'sub-folder' => 'ajax',
        'addon' => 'stripe'
    ),
    'params' => array(
        'id' => '(\d+)'
    ),

    'routes' => array(
        'ajax-stripe-pay-button' => array(
            'path' => '/ajax/stripe/get-button/{:invoice_id}/',
            'params' => array(
                'invoice_id' => '(\d+)'
            ),
            'values' => array(
                'controller' => 'StripeAjaxController',
                'action' => 'loadStripeButton'
            )
        )
    )

));