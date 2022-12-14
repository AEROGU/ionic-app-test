<template>
  <ion-page>
    <ion-header :translucent="true">
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button color="primary"></ion-menu-button>
        </ion-buttons>
        <ion-title>Nueva persona</ion-title>
      </ion-toolbar>
    </ion-header>

    <ion-content :fullscreen="true">
      <ion-header collapse="condense">
        <ion-toolbar>
          <ion-title size="large">Nueva persona</ion-title>
        </ion-toolbar>
      </ion-header>

      <div id="container" class=" bg-slate-50 items-center flex h-full">

        <div class="rounded p-5 my-2 md:my-5 mx-5 md:mx-28 my-auto w-full" style="background-color: rgba(91, 33, 182, 0.3);">
          <form id="newPersonForm">
            <label for="name">Nombre: </label><br />
            <input v-model="name" type="text" name="name" id="name" placeholder="Nombre de la persona"
              class="p-2 mb-3 min-w-full valid:border-green-500 invalid:border-red-500"
              required minlength="5" :disabled="sending" />
            <br />
            <label for="phone">Teléfono: </label><br />
            <input v-model="phone" type="text" name="phone" id="phone" placeholder="Teléfono"
              class="p-2 mb-3 min-w-full valid:border-green-500 invalid:border-red-500" required minlength="10"
              pattern="^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\./0-9]*$" :disabled="sending" />
            <br />
            <label for="email">E-Mail: </label><br />
            <input v-model="email" type="email" name="email" id="email" placeholder="Correo electrónico"
              class="p-2 mb-3 min-w-full valid:border-green-500 invalid:border-red-500"
              required :disabled="sending" />
            <br />
            <input v-model="latitude" type="hidden" name="latitude" id="latitude" class="p-2" />
            <input v-model="longitude" type="hidden" name="longitude" id="longitude" class="p-2" />

            <button type="button" class="rounded bg-indigo-700 text-white px-5 py-2 mt-2 mr-2 disabled:bg-slate-500"
              @click="getLocation(0)" :disabled="isLocating">
              Ubicar
            </button>
            <span v-show="located">
              Latitud: <span class="italic underline">{{ latitude }}</span>,
              Long: <span class="italic underline">{{ longitude }}</span>
            </span>

            <span v-show="isLocating">
              <div class="lds-dual-ring mr-5"></div>Obteniendo ubicación...
            </span>

            <hr class="my-2" />

            <div class="text-right">

              <span v-if="!located" class="mr-4 text-slate-400">Debe obtener la ubicación para poder guardar</span>
              <span v-if="sending">
                <div class="lds-dual-ring mr-5"></div>
                <span class="mr-4 text-slate-400">Creando usuario, espere por favor...</span>
              </span>

              <button type="button" class="rounded bg-indigo-700 text-white px-5 py-2 disabled:bg-slate-500"
                :disabled="!located || sending" @click="sendRequest">
                Crear
              </button>
            </div>
          </form>
        </div>


      </div>
    </ion-content>
  </ion-page>
</template>


<script lang="ts" setup>
import { IonButtons, IonContent, IonHeader, IonMenuButton, IonPage, IonTitle, IonToolbar, isPlatform } from '@ionic/vue';
import { Geolocation } from '@capacitor/geolocation';
import Swal from 'sweetalert2';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

import { serverPersonUrl } from '@/ServerConfig';
import axios from 'axios';

const router = useRouter();

// Status flags
let isLocating = ref(false);
let located = ref(false);
let sending = ref(false);

// Reactive form data
let name = ref("");
let phone = ref("");
let email = ref("");
let latitude = ref(0);
let longitude = ref(0);

function resetValues() {
  sending.value = false;
  name.value = "";
  phone.value = "";
  email.value = "";
  latitude.value = 0;
  longitude.value = 0;
  isLocating.value = false;
  located.value = false;
}

async function sendRequest() {
  sending.value = true;

  let headers = {
    "Content-Type": "application/json"
  };

  let data = {
    "name": name.value,
    "phone": phone.value,
    "email": email.value,
    "latitude": latitude.value,
    "longitude": longitude.value
  }

  console.log(data);

  axios.post(serverPersonUrl, data, { "headers": headers })
    .catch((error) => {
      if (error.request) {

        if (error.response.status === 0) { // Server not respondig
          Swal.fire({
            title: 'El servidor no responde',
            text: 'Asegúrate que el servidor esté dado de alta y configurado',
            icon: 'error',
            heightAuto: false,
          });
        } else if (error.response.status === 400) { //Bad request (validation errors)
          let responseEntries = Object.entries(error.response.data);
          let output = "";

          // Format response
          for (let i = 0; i < responseEntries.length; i++) {
            output += "<strong>" + responseEntries[i][0] + "</strong>:<br>"
            for (let j = 0; j < responseEntries[i][1].length; j++) {
              output += responseEntries[i][1][j] + "<br>";
            }
          }

          Swal.fire({
            title: 'Errores de validación',
            html: output,
            icon: 'error',
            heightAuto: false,
          });
        } else {
          console.log("Que carajos pasó aquí!")
          console.log(error);
        }

        console.log(error.response);

      }
    })
    .then(function (response) {
      if (response) {
        let id = response.data.id;
        router.push("/person/" + id);
      }
      sending.value = false;
      resetValues();
    });
}

async function getLocation(tries = 0) {

  let havePermission = await Geolocation.checkPermissions();

  if (havePermission.location == "denied") {

    if (tries > 0) {
      Swal.fire({
        title: 'Ubicación requerida',
        text: 'No autorizó la ubicación, no puede crear una persona sin ubicación.\n' +
          'Vuelva a pulzar el boton de ubicar para intentarlo de nuevo.',
        icon: 'error',
        heightAuto: false,
      });
      return;
    }

    // Check if the reason is because is not secure (https)
    try {
      let coordinatesError = await Geolocation.getCurrentPosition();
    } catch (e) {
      if (e.message.search('secure origin') != -1) {
        Swal.fire({
          title: 'No se puede obtener la ubicación en origen no seguro',
          text: 'Estás usando una versión web de esta App y el servidor no es HTTPS. \n',
          icon: 'error',
          heightAuto: false,
        });
        return;
      }
    }

    if (isPlatform('android') || isPlatform('ios')) {
      await Swal.fire({
        title: 'Permisos de ubicación requeridos',
        text: "Acontinuación se le solicitará los permisos de ubicación para poder continuar.",
        icon: 'info',
        heightAuto: false
      });

      await Geolocation.requestPermissions(); // Request permission, only for Android and iOS

      getLocation(tries + 1); // Try again
      return;
    } else {
      await Swal.fire({
        title: 'Permisos de ubicación requeridos',
        text: "Detectamos que está usando la versión de navegador de esta app.\n" +
          "Para continuar, por favor permita el acceso a su ubicación usando el " +
          "icono de ubicación en la barra de direcciónes, luego, haga clic en 'Reintentar' " +
          "para obtener sus coordenadas actuales.",
        icon: 'info',
        heightAuto: false
      });
      getLocation(tries + 1); // Try again
      return;
    }
  } else {

    isLocating.value = true;
    located.value = false;
    try {
      let coordinates = await Geolocation.getCurrentPosition();
      await setTimeout(function () {
        //
      }, 1000);
      coordinates = await Geolocation.getCurrentPosition(); // A second time increase the coordinates accuracy
      located.value = true;
      latitude.value = coordinates.coords.latitude;
      longitude.value = coordinates.coords.longitude;
    } catch (error: any) {
      console.log(error);
      await Swal.fire({
        title: 'Error al obtener la ubicación',
        text: error.message,
        icon: 'info',
        heightAuto: false
      });
    }
    isLocating.value = false;
  }
}

</script>

<style>
.lds-dual-ring {
  display: inline-block;
  width: 32px;
  height: 32px;
}

.lds-dual-ring:after {
  content: " ";
  display: block;
  width: 32px;
  height: 32px;
  margin: 8px;
  border-radius: 50%;
  border: 4px solid #fff;
  border-color: #fff transparent #fff transparent;
  animation: lds-dual-ring 1.2s linear infinite;
}

@keyframes lds-dual-ring {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>