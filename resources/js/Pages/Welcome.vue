<script setup>
import { ref, onMounted } from 'vue'
import { Link, Head } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
    movies: {
        type: Object,
        required: true
    }
})

const moviesList = ref([...props.movies.results])
const currentPage = ref(props.movies.page)
const hasMore = ref(props.movies.page < props.movies.total_pages)
const isLoading = ref(false)

const showModal = ref(false)
const selectedMovie = ref(null)

function openModal(movie) {
    selectedMovie.value = movie
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    selectedMovie.value = null
}

async function loadMoreMovies() {
    if (isLoading.value || !hasMore.value) return
    isLoading.value = true
    try {
        const nextPage = currentPage.value + 1
        const res = await axios.get(`/load-movies/${nextPage}`)

        if (res.data && res.data.results) {
            moviesList.value.push(...res.data.results)
            currentPage.value = res.data.page
            hasMore.value = res.data.page < res.data.total_pages
        }
    } catch (err) {
        console.error('Erro ao carregar mais filmes:', err)
    } finally {
        isLoading.value = false
    }
}

onMounted(() => {
    const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
            loadMoreMovies()
        }
    }, { threshold: 1.0 })

    const sentinel = document.querySelector('#sentinel')
    if (sentinel) {
        observer.observe(sentinel)
    }
})
</script>

<template>

    <Head>
        <title>TMDB Movies</title>
    </Head>
    <header>
        <h1>TMDB Movies</h1>
        <nav>
            <ul>
                <li>
                    <Link href="/">Início</Link>
                </li>
                <li>
                    <Link href="/login">Login</Link>
                </li>
                <li>
                    <Link href="/register">Register</Link>
                </li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2 class="title-section">Filmes Populares</h2>
        <div class="grid">
            <div class="card" v-for="movie in moviesList" :key="movie.id">
                <img :src="movie.backdrop_path ? 'https://image.tmdb.org/t/p/w500' + movie.backdrop_path : 'https://via.placeholder.com/500x750?text=Sem+Imagem'"
                    :alt="movie.title" />
                <div class="card-content">
                    <h3>{{ movie.original_title }}</h3>
                    <p>{{ movie.overview ? movie.overview.substring(0, 100) + '...' : 'Sem descrição disponível.' }}</p>
                    <button class="btn" @click="openModal(movie)">Ver Detalhes</button>
                </div>
            </div>
        </div>

        <div v-if="isLoading" class="loading">Carregando...</div>

        <div id="sentinel"></div>
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

<style>
.loading {
    text-align: center;
    padding: 20px;
    font-size: 18px;
    color: #e50914;
}

body {
    margin: 0;
    font-family: Arial, sans-serif;
    background-color: #141414;
    color: #fff;
}

/* Header */
header {
    background-color: #000;
    padding: 20px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 100;
}

header h1 {
    margin: 0;
    color: #e50914;
    font-size: 26px;
}

nav ul {
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

/* Container e Grid */
.container {
    max-width: 1200px;
    margin: 40px auto;
    padding: 0 20px;
}

.title-section {
    font-size: 24px;
    margin-bottom: 20px;
    border-left: 5px solid #e50914;
    padding-left: 10px;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
}

/* Cards */
.card {
    background-color: #1f1f1f;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
    transition: transform 0.3s, box-shadow 0.3s;
    cursor: pointer;
}

.card:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.7);
}

.card img {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.card-content {
    padding: 15px;
}

.card-content h3 {
    margin: 0 0 10px;
    font-size: 18px;
    color: #fff;
}

.card-content p {
    font-size: 14px;
    color: #aaa;
    line-height: 1.4;
}

.btn {
    display: inline-block;
    margin-top: 10px;
    padding: 8px 16px;
    background-color: #e50914;
    color: #fff;
    font-size: 14px;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.btn:hover {
    background-color: #b20710;
}

/* Modal */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 200;
}

.modal-content {
    background: #1f1f1f;
    padding: 20px;
    border-radius: 10px;
    width: 90%;
    max-width: 600px;
    color: #fff;
    position: relative;
}

.modal-content img {
    width: 100%;
    border-radius: 10px;
    margin-bottom: 15px;
}

.fechar {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 28px;
    cursor: pointer;
    color: #e50914;
}

footer {
    text-align: center;
    padding: 20px;
    margin-top: 40px;
    border-top: 1px solid #333;
    color: #666;
}
</style>
