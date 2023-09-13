<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ModalFormBook from '@/Components/ModalFormBook.vue';
import Swal from 'sweetalert2';
import { Head } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import axios from 'axios';

let books = ref([]);
let bookSelected = ref({});

onMounted(async () => {
  try {
    const response = await axios.get('/api/books');
    books.value = response.data.data;

  } catch (error) {
    console.error('Error al obtener datos de la API', error);
  }
});


const destroy = (id,name) =>{
    const swalWithBootstrapButtons = Swal.mixin({
        buttonsStyling:true
    })
    swalWithBootstrapButtons.fire({
        title:'Seguro de eliminar la canción '+name,
        text: 'Se perderá',
        icon:'question',
        showCancelButton:true,
        confirmButtonText: '<i class = "fa-solid fa-check"></i> Si, eliminar',
        cancelButtonText: '<i class = "fa-solid fa-check"></i> Cancelar'
    }).then((result) => {
        if(result.isConfirmed){
            axios.delete(`/api/books/${id}`);
            location.reload();
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
                    <!-- <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div> -->
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
