import Vue from 'vue'
import slugify from 'slugify'
import Vue2Filters from 'vue2-filters'
import vueFilterPrettyBytes from 'vue-filter-pretty-bytes'

Vue.use(Vue2Filters)
Vue.use(vueFilterPrettyBytes)

export default function ({ store, $dayjs }) {
  Vue.filter('fromNow', function (date) {
    return $dayjs(date).fromNow()
  })

  Vue.filter('formatShort', function (date) {
    return $dayjs(date).format('D/MM/YYYY')
  })

  Vue.filter('formatMedium', function (date) {
    return $dayjs(date).format('D MMMM YYYY')
  })

  Vue.filter('formatMediumWithTime', function (date) {
    return $dayjs(date).format('D MMMM YYYY à HH:mm')
  })

  Vue.filter('formatLong', function (date) {
    return $dayjs(date).format('D MMMM YYYY')
  })

  Vue.filter('formatLongWithTime', function (date) {
    return $dayjs(date).format('D MMMM YYYY - HH:mm')
  })

  Vue.filter('formatCustom', function (date, custom) {
    return $dayjs(date).format(custom)
  })

  Vue.filter('labelFromValue', function (key, taxonomy) {
    const element = store.getters.taxonomies[taxonomy].terms.find((el) => {
      return el.value == key
    })
    return element ? element.label : ''
  })

  Vue.filter('reseauFromValue', function (id) {
    const element = store.getters.reseaux.find((el) => {
      return el.id == id
    })
    return element ? element.name : ''
  })

  Vue.filter('departmentFromValue', function (key) {
    const department = store.getters.taxonomies.departments.terms.find((el) => {
      return el.value == key
    })
    return department ? department.label : ''
  })

  Vue.filter('fullDepartmentFromValue', function (key) {
    const department = store.getters.taxonomies.departments.terms.find((el) => {
      return el.value == key
    })
    return department ? `${department.value} - ${department.label}` : ''
  })

  Vue.filter('cleanCity', function (city) {
    return city.replace('Arrondissement', '')
  })

  Vue.filter('cleanDomaineAction', function (domaine) {
    switch (domaine) {
      case 'Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis':
        return 'Aide alimentaire et d’urgence'
      case 'Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance':
        return 'Garde exceptionnelle d’enfants'
      case 'Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)':
        return 'Lien avec les personnes fragiles isolées'
      case 'Je fais les courses de produits essentiels pour mes voisins les plus fragiles.':
        return 'Solidarité de proximité'
      case 'soutien_aux_personnes_agees_en_etablissement':
        return 'Soutien aux personnes âgées en établissement'
      case 'soutien_scolaire_a_distance':
        return 'Soutien scolaire'
      case 'fabrication_distribution_equipements':
        return 'Fabrication et distribution d’équipements de protection grand public'
      case 'soutien_mobilisation_sanitaire':
        return 'Soutien à la mobilisation sanitaire'
      case 'soutien_reprise_missions_service_public':
        return 'Soutien à la reprise des missions de service public'
      default:
        return domaine
    }
  })

  Vue.filter('domainIcon', function (domaine) {
    switch (domaine) {
      case 'Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis':
        return '/images/groceries.svg'

      case 'Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance':
        return '/images/teddy-bear.svg'

      case 'Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)':
        return '/images/phone-handle.svg'

      case 'Je fais les courses de produits essentiels pour mes voisins les plus fragiles.':
        return '/images/basket.svg'

      case 'soutien_aux_personnes_agees_en_etablissement':
        return '/images/ehpad.svg'

      case 'soutien_scolaire_a_distance':
        return '/images/ecole.svg'

      case 'fabrication_distribution_equipements':
        return '/images/masque.svg'

      case 'soutien_mobilisation_sanitaire':
        return '/images/sanitaire.svg'

      case 'soutien_reprise_missions_service_public':
        return '/images/service-public.svg'

      default:
        return null
    }
  })

  Vue.filter('slugify', function (string) {
    const options = {
      lower: true,
    }
    return string ? slugify(string, options) : string
  })

  Vue.filter('icoFromMimeType', function (mymeType) {
    switch (mymeType) {
      case 'application/vnd.ms-powerpoint':
      case 'application/vnd.openxmlformats-officedocument.presentationml.presentation':
        return 'file-powerpoint'
      case 'application/pdf':
        return 'file-pdf'
      case 'text/csv':
        return 'file-csv'
      case 'application/msword':
      case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
        return 'file-word'
      default:
        return 'file'
    }
  })

  Vue.filter('fileSizeBytes', function (size) {
    size = Math.abs(parseInt(size, 10))
    const def = [
      [1, 'octets'],
      [1024, 'ko'],
      [1024 * 1024, 'Mo'],
      [1024 * 1024 * 1024, 'Go'],
      [1024 * 1024 * 1024 * 1024, 'To'],
    ]
    for (let i = 0; i < def.length; i++) {
      if (size < def[i][0]) {
        return (size / def[i - 1][0]).toFixed(2) + ' ' + def[i - 1][1]
      }
    }
  })

  Vue.filter('fileSizeOctets', function (size) {
    size = Math.abs(parseInt(size, 10))
    const def = [
      [1, 'octets'],
      [1000, 'ko'],
      [1000 * 1000, 'Mo'],
      [1000 * 1000 * 1000, 'Go'],
      [1000 * 1000 * 1000 * 1000, 'To'],
    ]
    for (let i = 0; i < def.length; i++) {
      if (size < def[i][0]) {
        return (size / def[i - 1][0]).toFixed(2) + ' ' + def[i - 1][1]
      }
    }
  })

  Vue.filter('truncate', function (value, limit) {
    if (value.length > limit) {
      value = value.substring(0, limit - 3) + '...'
    }
    return value
  })

  Vue.filter('daysFromTimestamp', function (value) {
    return (value / (60 * 60 * 24)).toFixed(1)
  })
}
