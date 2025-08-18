<script setup>
import { ref, computed } from 'vue'
import { Link, router, Head } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    favorites: {
        type: Object,
        required: true
    },
    genres: {
        type: Array,
        required: true
    },
    user: String
})

const showModal = ref(false)
const selectedMovie = ref(null)
const message = ref('')
const showMessage = ref(false)
const selectedGenre = ref('')

const filteredMovies = computed(() => {
    if (!props.favorites.results) return []
    if (!selectedGenre.value) return props.favorites.results
    return props.favorites.results.filter(movie =>
        movie.genre_ids?.includes(Number(selectedGenre.value))
    )
})

function openModal(movie) {
    selectedMovie.value = movie
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedMovie.value = null
}

function logout() {
    router.post(route('logout'))
}

function removeFromFavorites(movieId) {
    axios.post(`remove-favorites/${movieId}`)
        .then(res => {
            message.value = res.data.message || 'Filme removido dos favoritos!'
            showMessage.value = true
            setTimeout(() => showMessage.value = false, 3000)
            router.reload()
        })
}
</script>

<template>

    <Head>
        <title>Meus Favoritos - TMDB Movies</title>
    </Head>
    <header class="header">
        <div class="header-left">
            <span class="welcome">Bem-vindo, {{ user }}</span>
            <h1>TMDB Movies</h1>
        </div>
        <div class="header-right">
            <nav>
                <ul>
                    <li>
                        <Link :href="route('dashboard')">Início</Link>
                    </li>
                    <li>
                        <Link :href="route('favorites')">Meus Favoritos</Link>
                    </li>
                    <li><button @click="logout" class="logout-btn">Sair</button></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">

        <div v-if="showMessage" class="api-message">{{ message }}</div>
        <div class="filter">
            <label for="genre">Filtrar por Gênero:</label>
            <div class="custom-select">
                <select id="genre" v-model="selectedGenre">
                    <option value="">Todos</option>
                    <option v-for="genre in genres" :key="genre.id" :value="genre.id">
                        {{ genre.name }}
                    </option>
                </select>
                <span class="arrow"></span>
            </div>
        </div>

        <h2 class="title-section">Meus Filmes Favoritos</h2>

        <div v-if="filteredMovies.length" class="grid">
            <div class="card" v-for="movie in filteredMovies" :key="movie.id">
                <img :src="movie.backdrop_path ? 'https://image.tmdb.org/t/p/w500' + movie.backdrop_path : 'https://via.placeholder.com/500x750?text=Sem+Imagem'"
                    :alt="movie.title" />
                <div class="card-content">
                    <h3>{{ movie.original_title }}</h3>
                    <p>{{ movie.overview ? movie.overview.substring(0, 100) + '...' : 'Sem descrição disponível.' }}</p>
                    <button class="btn" @click="openModal(movie)">Ver Detalhes</button>
                    <button class="btn remove-btn" @click="removeFromFavorites(movie.id)">💔</button>
                </div>
            </div>
        </div>

        <div v-else class="no-favorites">
            <p>Você ainda não tem nenhum filme nos favoritos.</p>
        </div>

    </div>

    <div class="modal" v-if="showModal">
        <div class="modal-content">
            <span class="fechar" @click="closeModal">&times;</span>
            <h2>{{ selectedMovie.original_title }}</h2>
            <img :src="selectedMovie.backdrop_path ? 'https://image.tmdb.org/t/p/w500' + selectedMovie.backdrop_path : 'https://via.placeholder.com/500x750?text=Sem+Imagem'"
                alt="" />
            <p><strong>Resumo:</strong> {{ selectedMovie.overview || 'Sem descrição disponível.' }}</p>
            <p><strong>Data de Lançamento:</strong> {{ selectedMovie.release_date || '-' }}</p>
            <p><strong>Popularidade:</strong> {{ selectedMovie.popularity }}</p>
            <p><strong>Nota Média:</strong> {{ selectedMovie.vote_average }}</p>
        </div>
    </div>

    <footer>TMDB Movies © 2025 - Todos os direitos reservados</footer>
</template>


<style scoped>
.api-message {
    position: fixed;
    top: 80px;
    right: 20px;
    background-color: #e50914;
    color: #fff;
    padding: 12px 20px;
    border-radius: 6px;
    z-index: 300;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    font-weight: bold;
    transition: opacity 0.3s;
}

.remove-btn {
    gap: 10px;
    margin-top: 10px;
    margin-left: 20px;
    background-color: #555;
}

.remove-btn:hover {
    background-color: #333;
}

.filter {
    margin-bottom: 30px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    max-width: 300px;
}

.filter label {
    font-weight: 600;
    color: #fff;
    font-size: 16px;
    margin-bottom: 4px;
}

.custom-select {
    position: relative;
}

.custom-select select {
    width: 100%;
    padding: 12px 40px 12px 16px;
    background-color: #1f1f1f;
    color: #fff;
    border: 2px solid #333;
    border-radius: 8px;
    appearance: none;
    font-size: 15px;
    cursor: pointer;
    transition: border-color 0.3s, background-color 0.3s;
}

.custom-select select:hover {
    border-color: #e50914;
    background-color: #292929;
}

.custom-select .arrow {
    position: absolute;
    top: 50%;
    right: 12px;
    width: 0;
    height: 0;
    pointer-events: none;
    border-left: 6px solid transparent;
    border-right: 6px solid transparent;
    border-top: 6px solid #fff;
    transform: translateY(-50%);
}

.custom-select select:focus {
    outline: none;
    border-color: #e50914;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
}

.header-left .welcome {
    font-size: 16px;
    color: #fff;
}
</style>
