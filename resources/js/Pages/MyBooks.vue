<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ModalFormBook from '@/Components/ModalFormBook.vue';
import Swal from 'sweetalert2';
import {Head, router } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

let books = ref([]);
let bookSelected = ref({});

onMounted(async () => {
    try {
        const userID = localStorage.getItem('UserId');
        const response = await axios.get('/api/books', {
            params: {
                user_id: userID
            }
        });
        books.value = response.data.data;

    } catch (error) {
        console.error(error);
    }
});

const destroy = (id, name) => {
    const swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling: true
    })
    swalWithBootstrapButtons.fire({
        title: 'Are you sure you want to delete the song ' + name,
        text: 'It will be lost',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check"></i> Yes, Delete',
        cancelButtonText: '<i class="fa-solid fa-check"></i> Cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                await axios.delete(`/api/books/${id}`);
                books.value = books.value.filter(book => book.id !== id);
                Swal.fire('Deleted', 'The book has been successfully deleted', 'success');
            } catch (error) {
                Swal.fire('Error', 'There was an error while deleting the book', 'error');
            }
        }
    })
};

</script>

<template>
    <Head title="My Books" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">My Books</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4 offset-md-4">
                <div class="d-grid mx-auto">
                    <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modalCreate">
                        <i class="fa-solid fa-circle-plus"></i>
                        ADD
                    </button>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-10 offset-md-1">
                <div class="table-responsive">
                    <table class="table table-stripeted table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Credit</th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="book in books" :key="book.id">
                                <td>
                                    <img :src="book.image_url" width="150" height="100">
                                </td>
                                <td>{{ book.title }}</td>
                                <td>{{ book.description }}</td>
                                <td>${{ book.credit }}</td>
                                <td>
                                    <button class="btn btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#modalEdit" @click="bookSelected = book">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-danger" @click="destroy(book.id, book.title)">
                                    <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <ModalFormBook :modal="'modalCreate'" :action="'create'" :book="bookSelected"></ModalFormBook>
        <ModalFormBook :modal="'modalEdit'" :action="'update'" :book="bookSelected"></ModalFormBook>
    </AuthenticatedLayout>
</template>
