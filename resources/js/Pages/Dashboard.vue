<script setup>
import { ref, reactive, onMounted, nextTick, onUnmounted } from 'vue'
import { Link, router, Head } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    movies: {
        type: Object,
        required: true
    },
    user: {
        type: String,
        required: true
    }
})

const moviesList = ref(props.movies.results || [])
const currentPage = ref(props.movies.page || 1)
const hasMore = ref(props.movies.page < props.movies.total_pages)
const isLoading = ref(false)
const showModal = ref(false)
const selectedMovie = ref(null)
const modalEl = ref(null)
const message = ref('')
const showMessage = ref(false)

const favoritesState = reactive({})

onMounted(() => {
    if (Array.isArray(props.movies?.results)) {
        props.movies.results.forEach(movie => {
            favoritesState[movie.id] = false
        })
    }
    window.addEventListener('scroll', handleScroll)
})

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
})

async function loadMoreMovies() {
    if (isLoading.value || !hasMore.value) return
    isLoading.value = true
    try {
        const nextPage = currentPage.value + 1
        console.log(currentPage.value)
        const res = await axios.get(`/load-movies/${nextPage}`)
        if (res.data && res.data.results) {
            moviesList.value.push(...res.data.results)
            currentPage.value = res.data.page
            hasMore.value = res.data.page < res.data.total_pages

            res.data.results.forEach(movie => {
                if (!(movie.id in favoritesState)) {
                    favoritesState[movie.id] = false
                }
            })
        }
    } catch (err) {
        console.error('Erro ao carregar mais filmes:', err)
    } finally {
        isLoading.value = false
    }
}

function handleScroll() {
    const scrollTop = window.scrollY
    const windowHeight = window.innerHeight
    const docHeight = document.documentElement.scrollHeight

    if (scrollTop + windowHeight >= docHeight - 100) {
        loadMoreMovies()
    }
}

function openModal(movie) {
    selectedMovie.value = movie
    showModal.value = true
    nextTick(() => {
        if (modalEl.value) modalEl.value.scrollTop = 0
    })
}

function closeModal() {
    showModal.value = false
    selectedMovie.value = null
}

function toggleFavorite(movieId) {
    try {
        axios.post(`/favorites/${movieId}`)
            .then(res => {
                favoritesState[movieId] = !favoritesState[movieId]
                message.value = res.data.message || (favoritesState[movieId]
                    ? 'Filme adicionado aos favoritos!'
                    : 'Filme removido dos favoritos!')
                showMessage.value = true
                setTimeout(() => showMessage.value = false, 3000)
            })
    } catch (error) {
        console.error('Erro ao atualizar favorito:', error)
    }

}

function logout() {
    router.post(route('logout'))
}
</script>

<template>

    <Head>
        <title>Dashboard - TMDB Movies</title>
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

        <div v-if="showMessage" class="message">{{ message }}</div>

        <h2 class="title-section">Filmes Populares</h2>

        <div class="grid">
            <div class="card" v-for="movie in moviesList" :key="movie.id">
                <img :src="movie.backdrop_path
                    ? 'https://image.tmdb.org/t/p/w500' + movie.backdrop_path
                    : 'https://via.placeholder.com/500x750?text=Sem+Imagem'" :alt="movie.title" />
                <div class="card-content">
                    <h3>{{ movie.original_title }}</h3>
                    <p>{{ movie.overview ? movie.overview.substring(0, 100) + '...' : 'Sem descrição disponível.' }}</p>

                    <div class="card-actions">
                        <button class="btn" @click="openModal(movie)">Ver Detalhes</button>
                        <button class="favorite-btn" @click="toggleFavorite(movie.id)"
                            :class="{ favorited: favoritesState[movie.id] }">
                            <span v-if="favoritesState[movie.id]">❤️</span>
                            <span v-else>🤍</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="loading">Carregando mais filmes...</div>
        <div v-else-if="!hasMore" class="end-message">Você chegou ao fim da lista 🎬</div>
    </div>

    <div v-if="showModal" class="modal" ref="modalEl">
        <div class="modal-content">
            <span class="fechar" @click="closeModal">&times;</span>
            <h2>{{ selectedMovie.original_title }}</h2>
            <img :src="selectedMovie.backdrop_path
                ? 'https://image.tmdb.org/t/p/w500' + selectedMovie.backdrop_path
                : 'https://via.placeholder.com/500x750?text=Sem+Imagem'" alt="" />
            <p><strong>Resumo:</strong> {{ selectedMovie.overview || 'Sem descrição disponível.' }}</p>
            <p><strong>Data de Lançamento:</strong> {{ selectedMovie.release_date || '-' }}</p>
            <p><strong>Popularidade:</strong> {{ selectedMovie.popularity }}</p>
            <p><strong>Nota Média:</strong> {{ selectedMovie.vote_average }}</p>
        </div>
    </div>

    <footer>TMDB Movies © 2025 - Todos os direitos reservados</footer>
</template>


<style scoped>
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #000;
    padding: 20px 40px;
    color: #fff;
    position: sticky;
    top: 0;
    z-index: 100;
}

.header-left {
    display: flex;
    align-items: center;
    gap: 15px;
    /* Espaço entre o welcome e o H1 */
}

.header-left h1 {
    margin: 0;
    color: #e50914;
    font-size: 26px;
}

.welcome {
    font-weight: bold;
    color: #fff;
    font-size: 16px;
}

.header-right nav ul {
    list-style: none;
    display: flex;
    gap: 20px;
    margin: 0;
    padding: 0;
}

nav ul li {
    cursor: pointer;
    font-weight: bold;
    transition: color 0.3s;
}

nav ul li:hover {
    color: #e50914;
}

.logout-btn {
    background: none;
    border: none;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: color 0.3s;
}

.logout-btn:hover {
    color: #e50914;
}

.card-actions {
    display: flex;
    gap: 10px;
    margin-top: 10px;
}

.favorite-btn {
    font-size: 18px;
    background: none;
    border: none;
    cursor: pointer;
    color: #aaa;
    transition: transform 0.2s, color 0.2s;
}

.favorite-btn:hover {
    transform: scale(1.2);
}

.favorite-btn.favorited {
    color: #e50914;
}

.message {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #e50914;
    color: #fff;
    padding: 12px 20px;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    z-index: 500;
    font-weight: bold;
    animation: fadeInOut 3s forwards;
}

@keyframes fadeInOut {
    0% {
        opacity: 0;
        transform: translateY(-10px);
    }

    10%,
    90% {
        opacity: 1;
        transform: translateY(0);
    }

    100% {
        opacity: 0;
        transform: translateY(-10px);
    }
}
</style>
