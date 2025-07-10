<template>
  <section class="d-flex flex-column gap-4">
    <div class="d-flex flex-column flex-md-row gap-2">
      <input v-model="search" type="text" class="form-control" placeholder="Szukaj ogłoszenia..." />
      <select v-model="sortOption" class="form-select w-auto">
        <option value="position">Sortuj: Domyślnie</option>
        <option value="price-asc">Cena rosnąco</option>
        <option value="price-desc">Cena malejąco</option>
        <option value="name-asc">Nazwa A-Z</option>
        <option value="name-desc">Nazwa Z-A</option>
      </select>
    </div>
    <div v-if="filteredAndSorted.length > 0" class="d-flex justify-content-end">
      <button class="btn btn-primary" @click="$emit('add')">➕ Dodaj ogłoszenie</button>
    </div>
    <div v-if="filteredAndSorted.length > 0" class="d-flex flex-column gap-4 w-100 col-md-6">
      <div v-for="a in filteredAndSorted" :key="a.id" class="border rounded shadow-sm p-3 mb-3 w-100"
        style="cursor: pointer;" @click="$emit('show', a)">
        <div class="d-flex justify-content-between align-items-center mb-2">
          <h5 class="mb-0">{{ a.name }}</h5>
          <span class="fw-bold">{{ a.price.toFixed(2) }} zł</span>
        </div>
        <p class="mb-2 text-muted text-truncate" style="max-width: 100%;">
          {{ a.description }}
        </p>
        <div v-if="a.images.length > 0" class="d-flex gap-2 flex-wrap">
          <img v-for="(img, i) in a.images" :key="i" :src="img" alt="Zdjęcie" style="height: 80px; object-fit: cover;"
            class="rounded border" />
        </div>
        <div v-else class="text-muted fst-italic">Brak zdjęć</div>
      </div>
    </div>
    <div v-else class="text-center w-100 col-md-6">
      <p class="text-muted">Brak ogłoszeń spełniających kryteria wyszukiwania.</p>
      <button class="btn btn-primary" @click="$emit('add')">➕ Dodaj pierwsze ogłoszenie</button>
    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { fetchAnnouncements } from '../api/api.js'

const announcements = ref([])
const search = ref('')
const sortOption = ref('position')

async function loadAnnouncements() {
  const response = await fetchAnnouncements()
  announcements.value = Array.isArray(response) ? response : response.data
}

onMounted(loadAnnouncements)

const filteredAndSorted = computed(() => {
  const list = Array.isArray(announcements.value) ? announcements.value : []
  let filtered = list.filter(a =>
    (a.name + a.description).toLowerCase().includes(search.value.toLowerCase())
  )
  switch (sortOption.value) {
    case 'price-asc':
      return filtered.sort((a, b) => a.price - b.price)
    case 'price-desc':
      return filtered.sort((a, b) => b.price - a.price)
    case 'name-asc':
      return filtered.sort((a, b) => a.name.localeCompare(b.name))
    case 'name-desc':
      return filtered.sort((a, b) => b.name.localeCompare(a.name))
    default:
      return filtered
  }
})

defineExpose({ loadAnnouncements })
</script>
