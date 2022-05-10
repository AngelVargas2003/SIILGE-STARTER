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
                <demo-datatable-basic style="width=100%"></demo-datatable-basic>
              </v-row>
            </v-form>
          </v-tab-item>

          <v-tab-item>
            <v-form class="multi-col-validation mt-1">
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
                  
                </v-col>


                <v-col
                  cols="12"
                  md="6"
                >
                </v-col>
              </v-row>
            </v-form>
          </v-tab-item>
        </v-tabs-items>
      </v-card-text>
      <v-divider></v-divider>
      <v-card-actions>
        <v-btn color="primary">
          Submit
        </v-btn>
        <v-btn
          color="secondary"
          outlined
        >
          Cancel
        </v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
import { reactive, ref } from '@vue/composition-api'
import { mdiEyeOutline, mdiEyeOffOutline } from '@mdi/js'
import DemoDatatableBasic from '../../../tables/datatable/demos/DemoDatatableBasic.vue'

export default {
  components: { DemoDatatableBasic },
  setup() {
    const tab = ref('')
    const form=reactive({
       Estado:null,
       Numero_Nucleos:null,
       NumeroHabitaciones:null,
       Refugio_id:null
    })

    return {
      tab,
      form     
    }
  },
}
</script>
