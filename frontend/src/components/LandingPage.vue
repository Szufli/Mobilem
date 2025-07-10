<template>
  <div
    class="app-wrapper bg-body-tertiary min-vh-100 d-flex flex-column align-items-center justify-content-start py-4 px-2">
    <div class="app-container bg-white rounded-4 shadow p-4 w-100" style="max-width: 1200px;">
      <header class="py-5 text-center shadow-sm w-100 rounded-3 mb-4">
        <div class="container">
          <h1 class="display-4 fw-bold">Ogłoszenia</h1>
          <p class="lead">Znajdź najlepsze oferty w swoim regionie</p>
        </div>
      </header>
    
      <transition name="fade">
        <div v-if="toastVisible" class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 11000;">
          <div class="toast show align-items-center text-white bg-success border-0 shadow" role="alert">
            <div class="d-flex">
              <div class="toast-body" aria-live="polite">
                {{ toastMessage }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto"
                @click="toastVisible = false"></button>
            </div>
          </div>
        </div>
      </transition>

      <main>
        <div class="px-3 px-md-5">
          <AnnouncementList @show="openViewDialog" @add="openCreateDialog" ref="announcementListRef" />
          <AnnouncementDialog :announcement="selected" :mode="dialogMode" :show="showModal" @close="closeDialog"
            @save="handleSave" @edit="enterEditMode" @delete="handleDelete" />
        </div>
      </main>

      <footer class="text-center text-muted py-4 border-top small mt-5">
        &copy; {{ new Date().getFullYear() }} Ogłoszenia App
      </footer>

    </div>
  </div>
  <div v-if="isLoading" class="loading-overlay">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
      <span class="visually-hidden">Ładowanie...</span>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import AnnouncementList from './AnnouncementList.vue'
import AnnouncementDialog from './AnnouncementDialog.vue'
import {
  createAnnouncement,
  updateAnnouncement,
  deleteAnnouncement,
} from '../api/api.js'

const isLoading = ref(false)
const selected = ref(null)
const showModal = ref(false)
const dialogMode = ref('view') // view | edit | create
const toastMessage = ref('')
const toastVisible = ref(false)
const announcementListRef = ref(null)

function openViewDialog(announcement) {
  selected.value = announcement
  dialogMode.value = 'view'
  showModal.value = true
}

function openCreateDialog() {
  selected.value = {
    name: '',
    description: '',
    price: 0,
    images: [],
  }
  dialogMode.value = 'create'
  showModal.value = true
}

function enterEditMode() {
  dialogMode.value = 'edit'
}

function closeDialog() {
  showModal.value = false
}

async function handleSave(data) {
  isLoading.value = true
  try {
    if (dialogMode.value === 'create') {
      await createAnnouncement(data)
      showToast('Ogłoszenie dodane!')
    } else if (dialogMode.value === 'edit' && selected.value?.id) {
      await updateAnnouncement(selected.value.id, data)
      showToast('Ogłoszenie zaktualizowane!')
    }
    showModal.value = false
    await announcementListRef.value.loadAnnouncements()
  } catch (error) {
    alert('Błąd podczas zapisu: ' + error.message)
  } finally {
    isLoading.value = false
  }
}

async function handleDelete() {
  isLoading.value = true
  try {
    await deleteAnnouncement(selected.value?.id)
    showToast('Ogłoszenie usunięte.')
    showModal.value = false
    await announcementListRef.value.loadAnnouncements()
  } catch (error) {
    alert('Błąd podczas usuwania: ' + error.message)
  } finally {
    isLoading.value = false
  }
}
function showToast(message) {
  toastMessage.value = message
  toastVisible.value = true
  setTimeout(() => {
    toastVisible.value = false
  }, 3000)
}
</script>
<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(255, 255, 255, 0.7);
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
  backdrop-filter: blur(3px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>