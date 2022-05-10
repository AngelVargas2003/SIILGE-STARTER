import { mdiCheckboxMarkedCircleOutline, mdiContentCopy } from '@mdi/js'

export default [
  {
    subheader: 'ADMIN-REFUGIOS',
  },
  {
      title:'Refugios',
      icon:mdiContentCopy,
      children:[
          {
              title:'Ver Refugios',
              to:'refugios'
          },
          {
              title:'Agregar/Modificar refugios',
              to:'adminrefugios'
          }
      ]
  }
]
