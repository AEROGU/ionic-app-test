<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Buscar por {{ searchByFormatted }}</ion-title>
      </ion-toolbar>
    </ion-header>
    
    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Buscar por {{ searchByFormatted }}</ion-title>
        </ion-toolbar>
      </ion-header>
    
      <div id="container">
        
        <p v-show="isLoadingPersons">Cargando personas...</p>

        <div class="text-center mt-10">
            <label for="search-input" class="mr-4">{{ searchByFormatted }}:</label>
            <input v-model="searchedValue" id="search-input" v-show="!isLoadingPersons" type="text" :placeholder="searchByFormatted + ' a buscar'" class="text-center p-1 text-lg mt-1">
            <ion-button v-show="!isLoadingPersons" color="tertiary" class="ion-activatable ripple-parent mt-1" @click="search">
                <SearchIcon class="fill-slate-100 text-2xl"></SearchIcon>
            </ion-button>
        </div>

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
import { useRouter, useRoute } from 'vue-router';
import { serverSearchPersonByEMailUrl, serverSearchPersonByPhoneUrl } from '@/ServerConfig';
import axios from 'axios';
import { ref, computed } from 'vue';
import SearchIcon from 'vue-ionicons/dist/md-search.vue'
import Swal from 'sweetalert2';

const router = useRouter();
const route = useRoute();

let searchBy = computed(() => route.fullPath.split("/")[3]); // email or phone

let searchedValue = ref("");

let searchByFormatted = computed(()=> searchBy.value == 'phone' ? 'Tel??fono' : 'E-Mail');

function searchUrl(value = "") {
    let output = searchBy.value == 'phone' ? serverSearchPersonByPhoneUrl : serverSearchPersonByEMailUrl;
    return output + value;
}

let persons = ref([]);

let isLoadingPersons = ref(false);

function search() {
  router.push("/person/search/" + searchBy.value + "/" + encodeURIComponent(searchedValue.value));
}

function loadPersons() {

  isLoadingPersons.value = true;
  axios.get(searchUrl(searchedValue.value))
  .catch((error) => {
      if (error.request) {
          if (error.response.status === 0) { // Server not respondig
              Swal.fire({
                  title: 'El servidor no responde',
                  text: 'Aseg??rate que el servidor est?? dado de alta y configurado',
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
  if (typeof(route.params[searchBy.value]) === 'undefined') {
    searchedValue.value = "";
  } else {
    searchedValue.value = String(route.params[searchBy.value]);
  }
  loadPersons();
});

</script>