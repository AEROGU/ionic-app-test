<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Todas las personas</ion-title>
      </ion-toolbar>
    </ion-header>
    
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Todoas las personas</ion-title>
        </ion-toolbar>
      </ion-header>
    
      <div id="container">

        <p v-show="isLoadingPersons">Cargando personas...</p>

        <ion-button v-show="!isLoadingPersons" color="tertiary" class="ion-activatable ripple-parent" @click="loadPersons">
          <RefreshIcon class="fill-slate-100 text-2xl"></RefreshIcon>
        </ion-button>

        <div v-if="!isLoadingPersons">
          <user-card v-for="person in persons" :key="person.id" :person="person"/>
        </div>

      </div>
    </ion-content>
  </ion-page>
</template>

<script lang="ts" setup>
import { IonButton, IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, onIonViewDidEnter } from '@ionic/vue';
import UserCard from '@/components/UserCard.vue';
import { useRouter } from 'vue-router';
import { serverPersonUrl } from '@/ServerConfig';
import axios from 'axios';
import { ref } from 'vue';
import RefreshIcon from 'vue-ionicons/dist/md-refresh.vue'
import Swal from 'sweetalert2';

const router = useRouter();
let persons = ref([]);

let isLoadingPersons = ref(false);

function loadPersons() {
  isLoadingPersons.value = true;
  axios.get(serverPersonUrl)
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
      persons.value = response.data;
      isLoadingPersons.value = false;
    }
    isLoadingPersons.value = false;
  });
}

onIonViewDidEnter(()=>{
  loadPersons();
});

</script>