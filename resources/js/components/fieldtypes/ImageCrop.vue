<template>
    <div class="flex h-full -mx-1">
        <div class="field-w-3/4 px-1">
            <div class="relative h-full mx-auto" :style="containerStyle">
                <img ref="cropper" :src="source" class="media" />
            </div>
        </div>
        <div class="field-w-1/4 px-1">
            <div ref="preview" class="overflow-hidden cropper-bg" :style="previewStyle" />

            <button class="btn mt-2" @click="handleReset">{{ __('Reset') }}</button>
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
        aspectRatio: {
            type: Number,
            required: true,
        },
    },
    data() {
        return {
            initial: this.value,
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
        previewStyle() {
            return { paddingBottom: `${100 / this.aspectRatio}%` }
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
            if (this.initial) {
                this.cropper.setData(this.initial)
            } else {
                this.cropper.reset()
            }
            this.$emit('input', this.initial)
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
</style>
