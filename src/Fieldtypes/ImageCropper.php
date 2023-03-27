<?php

namespace Tv2regionerne\StatamicImageCropper\Fieldtypes;

use Statamic\Fields\Fieldtype;

class ImageCropper extends Fieldtype
{
    protected $defaultable = true;
    protected $categories = ['media'];
    protected $icon = 'assets';

    protected function configFieldItems(): array
    {
        return [
            'source' => [
                'type' => 'text',
                'display' => __('Image Source Field'),
                'instructions' => __('What image field handle the cropper should use.'),
                'default' => 'image',
                'width' => 25,
                'validate' => [
                    'required',
                ],
            ],
            'dimensions' => [
                'type' => 'array',
                'display' => __('Dimensions'),
                'instructions' => __('List of dimensions with key and label like `16_9` and `16/9`'),
                'key_header' => __('Key'),
                'value_header' => __('Label'),
                'add_button' => __('Add Dimension'),
                'width' => 25,
                'validate' => [
                    'required',
                ],
            ],
        ];
    }

    /**
     * Pre-process the data before it gets sent to the publish page.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function preProcess($data)
    {
        return $data;
    }

    /**
     * Process the data before it gets saved.
     *
     * @param mixed $data
     * @return array|mixed
     */
    public function process($data)
    {
        return $data;
    }
}
