<template>
   <div>
    <v-tabs v-model="tab">
      <v-tab>Modificar Refugio</v-tab>
      <v-tab>Agregar Refugio</v-tab>
    </v-tabs>

    <v-card flat>
      <v-card-text>
        <v-tabs-items v-model="tab">
          <v-tab-item>
            <v-form class="multi-col-validation mt-1">
              <v-row>
                <demo-datatable-basic></demo-datatable-basic>
              </v-row>
            </v-form>
          </v-tab-item>

          <v-tab-item>
            <v-form class="multi-col-validation mt-1" @submit.prevent="submit">
              <v-row>
                <v-col
                  cols="12"
                  md="6"
                >
                  <v-text-field
                    v-model="form.Estado"
                    label="Estado"
                    outlined
                    dense
                    hide-details
                  ></v-text-field>
                </v-col>
                <v-col
                  cols="12"
                  md="6"
                >
                  
                  <v-text-field
                    v-model="form.NumeroHabitaciones"
                    label="Numero de Habitaciones"
                    outlined
                    dense
                    hide-details
                  ></v-text-field>
                </v-col>


                <v-col
                  cols="12"
                  md="6"
                >
                   <v-text-field
                    v-model="form.Numero_Nucleos"
                    label="Numero de Nucleos"
                    outlined
                    dense
                    hide-details
                  ></v-text-field>
                </v-col>
                <v-col>
                  <v-select
                  solo-inverted 
                  v-model="form.Refugio_id"
                  item-text="Nombre"
                  item-value="id" 
                  :items="form.refugios"
                  >
                  </v-select>
                </v-col>
                <v-col cols="12"
                md="6">
                  <v-btn color="primary" type="submit">
                  Registrar Refugio
                </v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-tab-item>
        </v-tabs-items>
      </v-card-text>
      <v-divider></v-divider>
    </v-card>
  </div>
</template>

<script>
import { onMounted, reactive, ref } from '@vue/composition-api'
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import DemoDatatableBasic from '../tables/datatable/demos/DemoDatatableBasic.vue';
import DemoComboboxBasic from '../forms/form-elements/combobox/demos/DemoComboboxBasic.vue';
import axios from 'axios';
export default {
  components: { DemoDatatableBasic,DemoComboboxBasic },
    setup() {
        const tab = ref("");
        const form = reactive({
            Estado: null,
            Numero_Nucleos: null,
            NumeroHabitaciones: null,
            Refugio_id: null,
            refugios:[]
        });
        function get(){
          axios.get('/refugios').then(function(response){
           form.refugios=response.data
          })
        }
        function submit(){
            axios.post('/refugio',form)
        }
        onMounted(get)
        return {
            tab,
            form,
            submit
        };
    },
}
</script>
