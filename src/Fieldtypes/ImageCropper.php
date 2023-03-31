<?php

namespace Tv2regionerne\StatamicImageCropper\Fieldtypes;

use Statamic\Fields\Fieldtype;

class ImageCropper extends Fieldtype
{
    protected $defaultable = true;

    protected $categories = ['media'];

    protected $icon = 'assets';

    /**
     * {@inheritdoc}
     */
    protected function configFieldItems(): array
    {
        return [
            'show_details' => [
                'type' => 'toggle',
                'display' => __('Show Details'),
                'instructions' => __('Whether additional details should be shown during cropping.'),
                'default' => true,
                'width' => 50,
            ],
            'source' => [
                'type' => 'text',
                'display' => __('Image Source Field'),
                'instructions' => __('What image field handle the cropper should use.'),
                'default' => 'image',
                'width' => 50,
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
                'width' => 50,
                'validate' => [
                    'required',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function augment($value)
    {
        if (is_array($value)) {
            return collect($value)
                ->map(function ($data) {
                    if ($data && isset($data['width'], $data['height'], $data['x'], $data['y'])) {
                        $data['crop'] = "{$data['width']},{$data['height']},{$data['x']},{$data['y']}";
                    }

                    return $data;
                })
                ->all();
        }

        return $value;
    }
}
