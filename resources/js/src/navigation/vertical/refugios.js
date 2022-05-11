import { mdiAbacus, mdiAccountNetwork, mdiCheckboxMarkedCircleOutline, mdiContentCopy, mdiHomeAccount, mdiHomeCircleOutline } from '@mdi/js'

export default [
  {
    subheader: 'ADMIN-REFUGIOS',
  },
  {
      title:'Refugios',
      icon:mdiHomeCircleOutline,
      children:[
          {
              title:'Ver Refugios',
              to:'refugios'
          },
          {
              title:'Agregar/Modificar refugios',
              to:'adminrefugios'
          }
      ],
  },
  {
    title:'Centro Externo',
    icon:mdiHomeAccount,
    children:[
      {
        title:'Referencias'
      },
      {
        title:'Concluye Proceso'
      }
    ]
  }
]
