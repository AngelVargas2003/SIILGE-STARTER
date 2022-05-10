<template>
  <v-data-table
    :headers="headers"
    :items="userData.refugios"
    :items-per-page="5"
  ></v-data-table>
</template>

<script>
import axios from "axios"
import { onMounted, reactive } from 'vue-demi'


export default {
  setup() {
    const userData = reactive({
      refugios:[]
    })
    function get(){
      axios.get('/refugio').then(function(response){
        userData.refugios=response.data
        console.log(userData.refugios)
      })
    }
    onMounted(get)
    return {
      headers: [
        { text: 'ID', sortable: false, value: 'id' },
        { text: 'REFUGIO', value: 'refugio' },
        { text: 'ESTADO', value: 'Estado' },
        { text: 'NUMERO DE NUCLEOS', value: 'numero_nucleos' },
        { text: 'NUMERO DE HABITACIONES', value: 'NumeroHabitaciones'},
      ],
      userData,
    }
  },
}
</script>
