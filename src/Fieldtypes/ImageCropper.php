<?php

namespace Tv2regionerne\StatamicImageCropper\Fieldtypes;

use Statamic\Facades\Entry;
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
                'instructions' => __('Whether to use manual, collection or taxonomy based dimensions.'),
                'default' => 'manual',
                'width' => 50,
                'options' => [
                    'manual' => __('Manual'),
                    'collection' => __('Collection'),
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
            'collection' => [
                'type' => 'collections',
                'display' => __('Collection'),
                'instructions' => __('The collection to use for dimensions.'),
                'max_items' => 1,
                'width' => 50,
                'mode' => 'select',
                'validate' => [
                    'required_if:mode,collection',
                ],
                'if' => [
                    'mode' => 'collection',
                ],
            ],
            'taxonomy' => [
                'type' => 'taxonomies',
                'display' => __('Taxonomy'),
                'instructions' => __('The taxonomy to use for dimensions.'),
                'max_items' => 1,
                'width' => 50,
                'mode' => 'select',
                'validate' => [
                    'required_if:mode,taxonomy',
                ],
                'if' => [
                    'mode' => 'taxonomy',
                ],
            ],
            'key_field' => [
                'type' => 'text',
                'display' => __('Key Field'),
                'instructions' => __('Which field to use for the key.'),
                'placeholder' => 'slug',
                'width' => 50,
                'unless' => [
                    'mode' => 'manual',
                ],
            ],
            'label_field' => [
                'type' => 'text',
                'display' => __('Label Field'),
                'instructions' => __('Which field to use for the label.'),
                'placeholder' => 'title',
                'width' => 50,
                'unless' => [
                    'mode' => 'manual',
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
        $mode = $this->config('mode', 'manual');

        if ($mode === 'manual') {
            return $this->config('dimensions');
        }

        if ($mode == 'taxonomy') {
            $query = Term::query()->where('taxonomy', $this->config('taxonomy'));
        } elseif ($mode == 'collection') {
            $query = Entry::query()->where('collection', $this->config('collection'));
        }

        $keyField = $this->config('key_field', 'slug');
        $labelField = $this->config('label_field', 'title');

        return $query->get()
            ->mapWithKeys(fn ($item) => [$item->{$keyField} => $item->{$labelField}])
            ->all();
    }
}
