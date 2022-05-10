<template>
  <v-row>
    <!-- basic -->
    <v-col cols="12">
        <demo-datatable-basic></demo-datatable-basic>
    </v-col>

    <!-- Table cell Slot for custom data rendering -->
   

    <!-- row selection -->

    <!-- fixed header -->
   

    <!-- grouping -->

    <!-- expandable rows -->
  

    <!-- Row Editing via dialog -->
   

    <!-- Cell Editing via dialog -->
    

    <!-- pagination -->
    

    <!-- kitchen sink -->
  </v-row>
</template>

<script>
import AppCardCode from '@/@core/components/app-card-code/AppCardCode.vue'

// demos
import DemoDatatableBasic from './demos/DemoDatatableBasic.vue'

// code
import {
  datatableBasic,
} from './demos/code'

export default {
  components: {
    AppCardCode,
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
