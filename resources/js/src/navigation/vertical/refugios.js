import { mdiAbacus, mdiAccountNetwork, mdiCheckboxMarkedCircleOutline, mdiContentCopy, mdiHomeAccount } from '@mdi/js'

export default [
  {
    subheader: 'ADMIN-REFUGIOS',
  },
  {
      title:'Refugios',
      icon:mdiAccountNetwork,
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
