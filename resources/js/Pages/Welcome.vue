<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const books = ref([]);
const filter = ref('');

onMounted(async () => {
    getBooks()  ;
});

const getBooks = async () => {
    try {
        const response = await axios.get('/api/books/details');
        books.value = response.data.data;

        if (filter.value) {
            books.value = books.value.filter(book => book.user?.name.toLowerCase().includes(filter.value.toLowerCase()));
        }
    } catch (error) {
        console.error('Error al obtener datos de la API', error);
    }
}

const filteredBooks = computed(() => {
    if (filter.value) {
        return books.value.filter(book => book.user?.name.toLowerCase().includes(filter.value.toLowerCase()));
    } else {
        return books.value;
    }
});


</script>

<template>
    <Head title="Welcome" />

    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
        <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right" style="width: 100%;background-color: #ffffff;">
            <Link
                :href="route('login')"
                class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Log in</Link
            >

            <Link
                v-if="canRegister"
                :href="route('register')"
                class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >Register</Link
            >
        </div>

        <div class="max-w-7xl mx-auto p-6 lg:p-8 mt-16">
                <div class="table-responsive">
                    <input type="text" v-model="filter" placeholder="Filter by author" class="filter-input" />
                    <table class="table table-stripeted table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Author</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Credit</th>
                                
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="book in filteredBooks" :key="book.id">
                                <td>
                                    <img :src="book.image_url" width="150" height="100">
                                </td>
                                <td>{{ book.user?.name }}</td>
                                <td>{{ book.title }}</td>
                                <td>{{ book.description }}</td>
                                <td>${{ book.credit }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</template>

<style>

.filter-input {
    margin-bottom: 1rem;
}

.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
