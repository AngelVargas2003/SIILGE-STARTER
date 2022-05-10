<template>
      <v-row>
    <v-col cols="12">
        <demo-datatable-basic></demo-datatable-basic>
    </v-col>
  </v-row>
</template>
<script>
// demos
import DemoDatatableBasic from './demos/DemoDatatableBasic.vue'

// code
import {
  datatableBasic,
} from './demos/code'

export default {
  components: {
    DemoDatatableBasic,
  },
  setup() {
    return {
      codeSnippets: {
        datatableBasic,
      },
    }
  },
}
</script>