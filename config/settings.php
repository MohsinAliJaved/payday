<?php

return [
    'user' => [
        ['name' => 'gender', 'value' => '', 'context' => 'user'],
        ['name' => 'contact', 'value' => '', 'context' => 'user'],
        ['name' => 'address', 'value' => '', 'context' => 'user'],
        ['name' => 'date_of_birth', 'value' => '', 'context' => 'user'],
    ],
    'app' => [
        ['name' => 'company_name', 'value' => env('APP_NAME', 'Email Marketing'), 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_logo', 'value' => '', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_icon', 'value' => '', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'company_banner', 'value' => '', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'language', 'value' => 'en', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'date_format', 'value' => 'd-m-Y', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_format', 'value' => 'h', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'time_zone', 'value' => 'UTC', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_symbol', 'value' => '$', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'decimal_separator', 'value' => '.', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'thousand_separator', 'value' => ',', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'number_of_decimal', 'value' => '2', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'currency_position', 'value' => 'prefix_with_space', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'application_form', 'value' => '[{"title":"Contact Details","type":"custom_field","key":"contact_details","isVisible":true,"icon":"globe","fields":[{"id":1,"name":"Phone","type":"tel-input","show":true,"require":true},{"id":2,"name":"Address","type":"textarea","show":true,"require":false},{"id":3,"name":"Linkedin","type":"text","show":true,"require":false},{"id":4,"name":"Twitter","type":"text","show":true,"require":false}]},{"title":"Education & Experience","type":"custom_field","key":"education_experience","isVisible":true,"icon":"bookmark","fields":[{"id":1,"name":"Education","type":"text","show":true,"require":false},{"id":2,"name":"Work experience","type":"text","show":true,"require":false}]},{"title":"Questions","type":"question","key":"questions","isVisible":true,"icon":"align-left","fields":[{"id":1,"name":"Write something about you...","type":"text","show":true,"require":true},{"id":2,"name":"Why you think you are good for this job?","type":"text","show":true,"require":false}]},{"title":"Assignment","type":"attachment","key":"assignment","isVisible":true,"icon":"file-text","fields":[{"id":1,"name":"Write your assignment question","type":"textarea","show":true,"require":true},{"id":2,"name":"Upload your attachment","type":"dropzone","show":true,"require":true}]},{"title":"Resume Upload","type":"attachment","key":"resume","isVisible":true,"icon":"upload","fields":[{"id":1,"name":"Upload your resume here","type":"dropzone","show":true,"require":true}]}]', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
        ['name' => 'career_page', 'value' => '{"content":{"title":"Come join with us","subtitle":"A fast growing software company build web apps","details":"Software Company - Dhaka","bodySection":[{"headings":"About Us","description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"},{"headings":"Service we provide","description":"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum"}]},"pageStyle":{"defaultView":[{"name":"Title","key":"title","fontSize":50,"fontWeight":700,"letterSpacing":1,"color":"#313131"},{"name":"Subtitle","key":"sub-title","fontSize":30,"fontWeight":300,"letterSpacing":1,"color":"#afb1b6"},{"name":"Details","key":"details","fontSize":20,"fontWeight":300,"letterSpacing":1,"color":"#3758b3"},{"name":"Headings","key":"headings","fontSize":27,"fontWeight":600,"letterSpacing":0,"color":"#313131"},{"name":"Description","key":"description","fontSize":19,"fontWeight":300,"letterSpacing":0,"color":"#313131"}],"mobileView":[{"name":"Title","key":"title","fontSize":40,"fontWeight":700,"letterSpacing":1,"color":"#313131"},{"name":"Subtitle","key":"sub-title","fontSize":25,"fontWeight":300,"letterSpacing":1,"color":"#afb1b6"},{"name":"Details","key":"details","fontSize":16,"fontWeight":300,"letterSpacing":1,"color":"#3758b3"},{"name":"Headings","key":"headings","fontSize":20,"fontWeight":600,"letterSpacing":0,"color":"#313131"},{"name":"Description","key":"description","fontSize":18,"fontWeight":300,"letterSpacing":0,"color":"#313131"}]},"pageBlocks":{"defaultView":{"header":true,"body":true,"footer":true,"logo":true},"mobileView":{"header":true,"body":true,"footer":true,"logo":true}}}', 'context' => 'tenant', 'autoload' => 0, 'public' => 1],
    ],
    'brand' => [
        ['name' => 'avatar', 'value' => null, 'context' => 'brand'],
        ['name' => 'address', 'value' => '', 'context' => 'brand'],
    ],
    'context' => [
        'app',
        'campaign',
        'list',
        'user',
        'segment',
        'subscriber',
        'brand',
        'role',
        'template'
    ],
    'time_format' => [
        'h',
        'H'
    ],
    'currency_position' => [
        'prefix_only',
        'prefix_with_space',
        'suffix_only',
        'suffix_with_space'
    ],
    'amazon_ses' => [
        'hostname' => '',
        'access_key_id' => '',
        'secret_access_key' => '',
    ],
    'mailgun' => [
        'domain_name' => '',
        'api_key' => '',
        'webhook_key' => ''
    ],
    'mail_configs' => [
        'context' => '',
        'from_email' => '',
        'from_name' => ''
    ],
    'date_format' => [
        'd-m-Y',
        'm-d-Y',
        'Y-m-d',
        'm/d/Y',
        'd/m/Y',
        'Y/m/d',
        'm.d.Y',
        'd.m.Y',
        'Y.m.d'
    ],

    'decimal_separator' => [
      '.',
      ','
    ],

    'thousand_separator' => [
        '.',
        ',',
        ' '
    ],
    'number_of_decimal' => [
        '0',
        '2'
    ],
    'supported_mail_services' => [
        'smtp' => 'SMTP',
        'amazon_ses' => 'Amazon SES',
        'mailgun' => 'Mailgun',
//        'mandrill' => 'Mandrill',
//        'postmark'=> 'Postmark',
//        'sparkpost' =>  'Sparkpost',
        'sendmail' => 'Sendmail'
    ],
    'corn-job-context' => 'corn-job',
    'brand_default_prefix' => [
        'amazon_ses' => 'brand_default_amazon_ses',
        'mailgun' => 'brand_default_mailgun',
        'privacy' => 'brand_default_privacy',
        'notification' => 'brand_default_notification',
    ],
    'supported_social_links' => [
        'facebook',
        'twitter',
        'linkedin',
        'behance',
        'instagram',
        'youtube',
        'dribble',
        'skype',
        'pinterest'
    ],
    'weekdays' => [ 'sun', 'mon', 'tue', 'wed', 'thu', 'fri', 'sat' ],
    'attendance_behavior' => [
        'regular' => 'success',
        'early' => 'warning',
        'late' => 'danger',
        'absent' => 'danger',
        'on_leave' => 'warning'
    ]

];
