<?php

return [
    'show_additional_message_info' => true,
    
    'file_upload_path' => 'public/uploads',

    //Defining default widgets
    'widgets'=> [
        'footer_widget_1' => 'App\AlderWidgets\FooterWidget1',
        'footer_widget_2' => 'App\AlderWidgets\FooterWidget2',
    ],
    
    'modifiers' => [
        Webcosmonauts\Alder\Models\Modifiers\AdminMenuItemModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\CapabilityModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\CategoryModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\ContactPageModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\PageModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\PostCategoryModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\PostModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\PostTagsModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\ReportModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\RoleModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\SeoModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\SiteMenuModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\TagModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\TestModifier::class,
        Webcosmonauts\Alder\Models\Modifiers\ToDownloadPageModifier::class,
    ],
];