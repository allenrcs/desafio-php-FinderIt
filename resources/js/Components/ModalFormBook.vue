<script setup>
    import { useForm, router } from '@inertiajs/vue3';
    import TextInput from '@/Components/TextInput.vue';
    import axios from 'axios';
    import {onMounted, ref, watch, reactive} from 'vue';

    const props = defineProps({
        modal:String,
        action:String,
        book:Object
    });

    let title = ref('');

    let form = useForm({
        id: '',
        title: '',
        description: '',
        credit: '',
        urlImage: ''
    })

    onMounted(() => {
        title.value = props.action === 'create' ? 'Add Book' : 'Update Book';
    });

    watch(() => props.book, (first, second) => {
        if (props.action === 'update' && first) {
            form.id = first.id,
            form.title = first.title,
            form.description = first.description,
            form.credit = first.credit,
            form.urlImage = first.urlImage
        }
    });

    let img = ref(null);

    const save = async () => {
        const response = await axios.post('/api/books', {
                title: form.title,
                description: form.description,
                credit: form.credit,
                image: img
        },
        {
            headers: {
              'Content-Type': 'multipart/form-data'
            }
        });
        location.reload();
    }

    const update = async () => {
      try {
        await axios.put(`/api/books/${form.id}`, {
          id: form.id,
          title: form.title,
          description: form.description,
          credit: form.credit,
          urlImage: form.urlImage
        });
        location.reload();
      } catch (error) {
        console.error('Error updating the book:', error);
      }
    }

    const close = () => {
        form.reset();
        document.querySelector('#close'+props.action).click();
    }

    const imagenSeleccionada = (event) => {
        img = event.target.files[0];
        console.log(img);
    }
</script>

<template>
    <div class="modal fade" :id="modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <label class="h5">{{ title }}</label>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="(action === 'create' ? save() : update())">
                        <TextInput :id="'id'+action" type="hidden" name="id" v-model="form.id">
                        </TextInput>
                        <div class="input-group mb-3">
                            <TextInput :id="'title'+action" class="form-control" type="text" 
                            name="title" v-model="form.title" maxlenght="120" placeholder="Title" required>
                            </TextInput>
                        </div>

                        <div class="input-group mb-3">
                            <TextInput :id="'autor'+action" class="form-control" type="text" 
                            name="autor" v-model="form.description" maxlenght="100" placeholder="Description" required>
                            </TextInput>
                        </div>

                        <div class="input-group mb-3">
                            <TextInput  class="form-control" type="number" 
                            name="album" v-model="form.credit" placeholder="Credits" required>
                            </TextInput>
                        </div>

                        <div class="input-group mb-3">
                            <input type="file" v-on="form.urlImage" accept=".png, .jpg" @change="imagenSeleccionada($event)">
                        </div>

                        <div class="d-grid-mx-auto">
                            <button class="btn btn-success">
                                <i class="fa-solid fa-floppy-disk"></i> Save
                            </button>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" type="submit" :id="'close'+action" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
    
</template>