<template>
    <form-refugios-vue></form-refugios-vue>
</template>
<script>
import {
  formLayoutTabs,
} from './forms/form-layout/demos/code'
import FormRefugiosVue from './MisComponentes/FormRefugios.vue'
export default {
  components: {
    FormRefugiosVue
  },
  setup() {
    return {
      codeSnippets: {
      },
    }
  },
}
</script>