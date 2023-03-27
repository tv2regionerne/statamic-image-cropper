<?php

namespace Tv2regionerne\StatamicImageCropper;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $fieldtypes = [
        Fieldtypes\ImageCropper::class,
    ];

    protected $vite = [
        'resources/js/addon.js',
    ];

    public function bootAddon()
    {
        //
    }
}
