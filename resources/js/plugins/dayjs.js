import Vue from 'vue'
import dayjs from 'dayjs'
import relativeTime from 'dayjs/plugin/relativeTime'
import 'dayjs/locale/fr'

dayjs.extend(relativeTime)
dayjs.locale('fr') // use Spanish locale globally

Vue.filter('fromNow', function (date) {
  return dayjs(date).fromNow()
})

Vue.filter('formatShort', function (date) {
  return dayjs(date).format('D/MM/YYYY')
})

Vue.filter('formatMedium', function (date) {
  return dayjs(date).format('D MMMM YYYY')
})

Vue.filter('formatMediumWithTime', function (date) {
  return dayjs(date).format('D MMMM YYYY à HH:mm')
})

Vue.filter('formatLong', function (date) {
  return dayjs(date).format('D MMMM YYYY')
})

Vue.filter('formatLongWithTime', function (date) {
  return dayjs(date).format('DD/MM/YYYY HH:mm')
})

Vue.filter('formatCustom', function (date, custom) {
  return dayjs(date).format(custom)
})
