<template>
    <div v-if="source">
        <div class="buttons -mx-.5">
            <button
                v-for="dimension in dimensions"
                :key="dimension.key"
                class="btn mx-.5 w-24"
                :class="{ 'text-primary': crops[dimension.key] !== null }"
                @click="openCropper(dimension)"
            >
                {{ dimension.label }}
            </button>
        </div>

        <stack v-if="cropper" name="crop-editor" full>
            <div class="flex flex-col bg-white h-full p-3">
                <ImageCrop v-model="shadow" :source="source" :aspect-ratio="dimension.ratio" />

                <div class="mt-2 -mx-1 text-right">
                    <button class="btn mx-1" @click="closeCropper">{{ __('Cancel') }}</button>
                    <button class="btn-primary mx-1" @click="saveCropper">{{ __('Save') }}</button>
                </div>
            </div>
        </stack>
    </div>
    <div v-else v-text="__('Select an image to start cropping.')" />
</template>

<script>
import ImageCrop from './ImageCrop.vue'

export default {
    mixins: [Fieldtype],
    components: { ImageCrop },
    inject: ['storeName'],
    data() {
        return {
            dimension: null,
            cropper: false,
            shadow: null,
            crops: this.prepareCrops(this.value),
        }
    },
    computed: {
        dimensions() {
            return Object.entries(this.config.dimensions).map(([key, label]) => {
                const [width, height] = key.split('_')
                const ratio = width / height

                return { key, label, ratio }
            })
        },
        sourceMeta() {
            if (!this.namePrefix) {
                return this.$store.state.publish[this.storeName].meta
            }

            let parent = this.$parent.$parent
            while (parent.meta === undefined) {
                parent = parent.$parent
            }

            return parent.meta
        },
        source() {
            const asset = this.sourceMeta[this.config.source].data?.[0]

            return asset?.isImage ? asset.url : null
        },
    },
    watch: {
        source: {
            immediate: true,
            handler(cur, old) {
                if (old && cur !== old) {
                    this.crops = this.prepareCrops()
                    this.update(this.crops)
                }
            },
        },
    },
    methods: {
        prepareCrops(value) {
            const crops = Object.assign({}, ...Object.keys(this.config.dimensions).map(key => ({ [key]:  null })))

            return value ? Object.assign(crops, value) : crops
        },
        openCropper(dimension) {
            this.dimension = dimension
            this.shadow = this.crops[this.dimension.key]
            this.cropper = true
        },
        closeCropper() {
            this.cropper = false
            this.dimension = null
            this.shadow = null
        },
        saveCropper() {
            this.crops[this.dimension.key] = this.shadow
            this.closeCropper()
            this.update(this.crops)
        },
    },
}
</script>
