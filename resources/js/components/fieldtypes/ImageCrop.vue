<template>
    <div class="flex h-full -mx-1">
        <div class="field-w-3/4 px-1">
            <div class="relative h-full mx-auto" :style="containerStyle">
                <img ref="cropper" :src="source" class="media" />
            </div>
        </div>
        <div class="field-w-1/4 px-1">
            <div ref="preview" class="overflow-hidden">
                <img :src="source" />
            </div>

            <div class="flex">
                <button class="btn mt-2" @click="handleReset">{{ __('Reset') }}</button>

                <dl class="inline-block mt-2 ml-2 text-sm" v-if="showDetails && value">
                    <div class="flex border-1 border-grey-40 rounded">
                        <dt class="w-20 py-.5 px-1.5 bg-grey-40">{{ __('X') }}</dt>
                        <dd class="w-20 py-.5 px-1.5 text-right">{{ value.x }}px</dd>
                    </div>
                    <div class="flex border-1 border-grey-40 rounded mt-.5">
                        <dt class="w-20 py-.5 px-1.5 bg-grey-40">{{ __('Y') }}</dt>
                        <dd class="w-20 py-.5 px-1.5 text-right">{{ value.y }}px</dd>
                    </div>
                    <div class="flex border-1 border-grey-40 rounded mt-.5">
                        <dt class="w-20 py-.5 px-1.5 bg-grey-40">{{ __('Width') }}</dt>
                        <dd class="w-20 py-.5 px-1.5 text-right">{{ value.width }}px</dd>
                    </div>
                    <div class="flex border-1 border-grey-40 rounded mt-.5">
                        <dt class="w-20 py-.5 px-1.5 bg-grey-40">{{ __('Height') }}</dt>
                        <dd class="w-20 py-.5 px-1.5 text-right">{{ value.height }}px</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</template>

<script>
import Cropper from 'cropperjs'
import 'cropperjs/dist/cropper.css'

export default {
    name: 'ImageCrop',
    props: {
        value: Object,
        source: {
            type: String,
            required: true,
        },
        aspectRatio: Number,
        showDetails: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            cropper: null,
            dimension: null,
        }
    },
    computed: {
        containerStyle() {
            if (this.dimension) {
                const { width, height } = this.dimension

                return { width: `${width}px !important`, height: `${height}px !important` }
            }

            return null
        },
    },
    mounted() {
        this.$refs.cropper.onload = () => {
            const { width, height } = this.$refs.cropper
            this.dimension = { width, height }
            this.setupCropper()
        }
    },
    beforeDestroy() {
        this.cropper.destroy()
    },
    methods: {
        setupCropper() {
            const { cropper, preview } = this.$refs
            this.cropper = new Cropper(cropper, {
                aspectRatio: this.aspectRatio,
                autoCropArea: 1,
                data: this.value,
                preview: preview,
                responsive: true,
                zoomable: false,
                crop: evt => {
                    const { detail: { x, y, width, height } } = evt
                    this.$emit('input', {
                        x: Math.max(0, Math.round(x)),
                        y: Math.max(0, Math.round(y)),
                        width: Math.round(width),
                        height: Math.round(height),
                    })
                },
            })
        },
        handleReset() {
            this.cropper.reset()
            this.$emit('input', null)
        },
    },
}
</script>

<style scoped>
.media {
    max-width: 100%;
    max-height: 100%;
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
}
.border-1 {
    border-width: 1px;
}
.w-20 {
    width: 5rem;
}
</style>
