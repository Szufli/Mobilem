<template>
    <div v-if="show" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">{{ modeTitle }}</h5>
                    <button type="button" class="btn-close" @click="$emit('close')"></button>
                </div>

                <form @submit.prevent="save">
                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nazwa</label>
                            <input v-model="form.name" class="form-control" :disabled="isView"
                                :class="{ 'is-invalid': errors.name }" />
                            <div v-if="errors.name" class="invalid-feedback">{{ errors.name }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Opis</label>
                            <textarea v-model="form.description" class="form-control" rows="4" :disabled="isView"
                                :class="{ 'is-invalid': errors.description }"></textarea>
                            <div v-if="errors.description" class="invalid-feedback">{{ errors.description }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Cena (zł)</label>
                            <input v-model.number="form.price" type="number" step=".01" class="form-control"
                                :disabled="isView" :class="{ 'is-invalid': errors.price }" />
                            <div v-if="errors.price" class="invalid-feedback">{{ errors.price }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Galeria zdjęć</label>
                            <div class="d-flex flex-wrap gap-2">

                                <div v-for="(img, i) in form.existingImages" :key="'existing-' + i"
                                    style="width: 100px; height: 100px; position: relative;">
                                    <img :src="img" class="img-thumbnail w-100 h-100 object-fit-cover"
                                        style="cursor: pointer;" @click="openPreview(img, i)" />
                                    <button v-if="isEditOrCreate" type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                        @click="removeExistingImage(i)">
                                        &times;
                                    </button>
                                </div>

                                <div v-for="(file, i) in form.newImages" :key="'new-' + i"
                                    style="width: 100px; height: 100px; position: relative;">
                                    <img :src="file.preview" class="img-thumbnail w-100 h-100 object-fit-cover"
                                        style="cursor: pointer;"
                                        @click="openPreview(file.preview, i + form.existingImages.length)" />
                                    <button v-if="isEditOrCreate" type="button"
                                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1"
                                        @click="removeNewImage(i)">
                                        &times;
                                    </button>
                                </div>

                                <label v-if="isEditOrCreate && totalImages < 5"
                                    class="d-flex align-items-center justify-content-center border border-2 border-secondary rounded"
                                    style="width: 100px; height: 100px; cursor: pointer;">
                                    <span class="text-muted fs-3">+</span>
                                    <input type="file" accept="image/*" class="d-none" @change="handleImageUpload" />
                                </label>
                            </div>

                            <div v-if="totalImages === 0 && isView" class="text-muted small mt-2">
                                Brak zdjęć
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="$emit('close')">
                            Zamknij
                        </button>
                        <button v-if="isView" type="button" class="btn btn-outline-primary" @click="$emit('edit')">
                            Edytuj
                        </button>
                        <button v-if="isEditOrCreate" type="submit" class="btn btn-primary">
                            Zapisz
                        </button>
                        <button v-if="isEdit" type="button" class="btn btn-danger" @click="confirmDeleteVisible = true">
                            Usuń
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div v-if="previewImage" class="modal fade show d-block" tabindex="-1"
            style="background-color: rgba(0,0,0,0.6); z-index: 1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <img :src="previewImage" class="w-100" />
                    <div class="modal-footer justify-content-between">
                        <small class="text-muted">Zdjęcie {{ previewIndex + 1 }}</small>
                        <button class="btn btn-secondary btn-sm" @click="previewImage = null">
                            Zamknij
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="confirmDeleteVisible" class="modal fade show d-block" tabindex="-1"
        style="background-color: rgba(0,0,0,0.5); z-index: 1065;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" @click="confirmDeleteVisible = false"></button>
                </div>
                <div class="modal-body">
                    <p>Czy na pewno chcesz usunąć to ogłoszenie?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        @click="confirmDeleteVisible = false">Anuluj</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Usuń</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, computed, ref, watch } from 'vue'
const MAX_PRICE = 9999999999.99
const props = defineProps({
    show: Boolean,
    announcement: Object,
    mode: { type: String, default: 'view' }, // 'view' | 'edit' | 'create'
})
const emit = defineEmits(['close', 'save', 'edit', 'delete'])
const confirmDeleteVisible = ref(false)
const form = reactive({
    name: '',
    description: '',
    price: 0,
    existingImages: [],
    newImages: [],
})
const errors = reactive({
    name: '',
    description: '',
    price: '',
})

const previewImage = ref(null)
const previewIndex = ref(0)

const totalImages = computed(() => form.existingImages.length + form.newImages.length)
const isView = computed(() => props.mode === 'view')
const isEditOrCreate = computed(() => props.mode === 'edit' || props.mode === 'create')
const isEdit = computed(() => props.mode === 'edit')
const modeTitle = computed(() => {
    switch (props.mode) {
        case 'create':
            return 'Nowe ogłoszenie'
        case 'edit':
            return 'Edytuj ogłoszenie'
        case 'view':
            return props.announcement?.name ?? 'Ogłoszenie'
    }
    return ''
})

function clearErrors() {
    errors.name = ''
    errors.description = ''
    errors.price = ''
}

function validate() {
    clearErrors()
    let valid = true

    if (!form.name.trim()) {
        errors.name = 'Nazwa jest wymagana'
        valid = false
    }
    if (form.name.trim() && form.name.length > 255) {
        errors.name = 'Nazwa może posiadać maksymalnie 255 znaków'
        valid = false
    }
    if (!form.description.trim()) {
        errors.description = 'Opis jest wymagany'
        valid = false
    }
    if (form.description.trim() && form.description.length > 1000) {
        errors.description = 'Opis może posiadać maksymalnie 1000 znaków'
        valid = false
    }
    if (form.price <= 0) {
        errors.price = 'Cena musi być większa od zera'
        valid = false
    }
    if (form.price > MAX_PRICE) {
        errors.price = `Cena nie może być większa niż ${MAX_PRICE.toFixed(2)}`
        valid = false
    }

    return valid
}

function removeExistingImage(index) {
    form.existingImages.splice(index, 1)
}
function removeNewImage(index) {
    URL.revokeObjectURL(form.newImages[index].preview)
    form.newImages.splice(index, 1)
}

function handleImageUpload(event) {
    const file = event.target.files[0]
    if (!file) return
    if (totalImages.value >= 5) {
        alert('Można dodać maksymalnie 5 zdjęć.')
        return
    }
    const preview = URL.createObjectURL(file)
    form.newImages.push({ file, preview })
    event.target.value = null
}

function openPreview(imgSrc, index) {
    previewImage.value = imgSrc
    previewIndex.value = index
}

async function save() {
    if (!validate()) return

    const formData = new FormData()
    formData.append('name', form.name)
    formData.append('description', form.description)
    formData.append('price', form.price)

    const existingImagesToSend = form.existingImages.map(img => {
        if (typeof img === 'string') {
            const parts = img.split('/storage/')
            return parts.length > 1 ? parts[1] : img
        }
        return img
    })

    existingImagesToSend.forEach((path, index) => {
        formData.append(`existingImages[${index}]`, path)
    })

    form.newImages.forEach(({ file }, i) => {
        formData.append('newImages[]', file)
    })

    emit('save', formData)
}

function confirmDelete() {
    confirmDeleteVisible.value = false
    emit('delete')
}

watch(
    () => props.announcement,
    (val) => {
        if (!val) return

        form.name = val.name ?? ''
        form.description = val.description ?? ''
        form.price = val.price ?? 0
        form.existingImages = Array.isArray(val.images) ? [...val.images] : []
        form.newImages = []
        clearErrors()
    },
    { immediate: true }
)
watch(
    () => form.price,
    (newVal) => {
        if (typeof newVal === 'string' && newVal.includes(',')) {
            form.price = parseFloat(newVal.replace(',', '.'))
        }
    }
)

</script>

<style scoped>
.modal {
    z-index: 1050;
}
</style>
