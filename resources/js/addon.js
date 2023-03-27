import ImageCropper from './components/fieldtypes/ImageCropper.vue'

Statamic.booting(() => {
    Statamic.component('image_cropper-fieldtype', ImageCropper)
})
