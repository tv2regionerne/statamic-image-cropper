<?php

namespace Tv2regionerne\StatamicImageCropper\Fieldtypes;

use Statamic\Facades\Term;
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
            'mode' => [
                'type' => 'button_group',
                'display' => __('Mode'),
                'instructions' => __('Wheher to use manual or taxonomy based dimensions.'),
                'default' => 'manual',
                'width' => 50,
                'options' => [
                    'manual' => __('Manual'),
                    'taxonomy' => __('Taxonomy'),
                ],
            ],
            'dimensions' => [
                'type' => 'array',
                'display' => __('Dimensions'),
                'instructions' => __('Dimensions with key like `16_9` for aspect ratio or free form otherwise.'),
                'key_header' => __('Key'),
                'value_header' => __('Label'),
                'add_button' => __('Add Dimension'),
                'width' => 50,
                'validate' => [
                    'required_if:mode,manual',
                ],
                'if' => [
                    'mode' => 'manual',
                ],
            ],
            'taxonomy' => [
                'type' => 'taxonomies',
                'display' => __('Taxonomy'),
                'instructions' => __('The taxonomy to use for dimensions.'),
                'max_items' => 1,
                'width' => 50,
                'validate' => [
                    'required_if:mode,taxonomy',
                ],
                'if' => [
                    'mode' => 'taxonomy',
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

    /**
     * {@inheritdoc}
     */
    public function preload()
    {
        return [
            'dimensions' => $this->getDimensions(),
        ];
    }

    /**
     * Get the dimensions.
     *
     * @return array
     */
    protected function getDimensions()
    {
        if ($this->config('mode', 'manual') === 'manual') {
            return $this->config('dimensions');
        }

        return Term::query()
            ->where('taxonomy', $this->config('taxonomy'))
            ->get()
            ->mapWithKeys(function ($term) {
                return [$term->slug() => $term->title()];
            })
            ->all();
    }
}
