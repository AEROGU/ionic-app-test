<template>
    <div
    :class="
    {
        'bg-violet-800 text-white rounded p-3 my-5 mx-5 md:mx-28': true,
        'hover:bg-violet-500 cursor-pointer': clickable
    }
    "
    @click="cardClick(person.id)">
        <p><strong>Nombre: </strong><i>{{ person.name }}</i></p>
        <p><strong>Teléfono: </strong><i>{{ person.phone }}</i></p>
        <p><strong>E-Mail: </strong><i>{{ person.email }}</i></p>
        <p><strong>Latitud: </strong><i>{{ person.latitude }}</i><strong> Longitud: </strong><i>{{ person.longitude }}</i></p>
    </div>
    <div v-if="showEditButtons">
        <hr>
        <div class="text-right space-x-2 mr-4">
            <!-- <ion-button><CreateIcon class="text-xl mr-2" />Editar</ion-button> -->
            <ion-button v-show="!isDeleting" color="danger" @click="deletePerson"><TrashIcon class="text-xl mr-2" />Borrar</ion-button>
            <span v-if="isDeleting">Borrando...</span>
        </div>
    </div>
</template>

<script lang="ts" setup>

import { defineProps, ref, defineEmits } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import CreateIcon from 'vue-ionicons/dist/md-create.vue'
import TrashIcon from 'vue-ionicons/dist/md-trash.vue'
import { IonButton } from '@ionic/vue';
import axios from 'axios';
import { serverPersonUrl } from '@/ServerConfig';
import Swal from 'sweetalert2';

const emit = defineEmits(['updateRequired']);

const router = useRouter();
const route = useRoute();

let isDeleting = ref(false);

const props = defineProps({
    person: Object,
    showEditButtons: { type: Boolean, default: false},
    clickable: { type: Boolean, default: true},
});

function cardClick(id) {
    if (this.props.clickable) {
        router.push("/person/" + id);
    }
}

function deletePerson() {
    isDeleting.value = true;
    axios.delete(serverPersonUrl + route.params.id)
    .catch((error) => {
        if (error.request) {
            if (error.response.status === 0) { // Server not respondig
                Swal.fire({
                    title: 'El servidor no responde',
                    text: 'Asegúrate que el servidor esté dado de alta y configurado',
                    icon: 'error',
                    heightAuto: false,
                });
            }
        }
    })
    .then((response) => {
        if (response) {
            router.push("/person/");
            emit('updateRequired');
        }
        isDeleting.value = false;
    });
}

</script>