import Vue from 'vue'
import lang from 'element-ui/lib/locale/lang/fr'
import locale from 'element-ui/lib/locale'
import './element-ui.scss'

locale.use(lang)

export default () => {
  Vue.component('ElDropdown', () =>
    import(
      /* webpackChunkName: 'element-ui-dropdown' */ 'element-ui/lib/dropdown'
    )
  )
  Vue.component('ElDropdownItem', () =>
    import(
      /* webpackChunkName: 'element-ui-dropdown-item' */ 'element-ui/lib/dropdown-item'
    )
  )
  Vue.component('ElDropdownMenu', () =>
    import(
      /* webpackChunkName: 'element-ui-dropdown-menu' */ 'element-ui/lib/dropdown-menu'
    )
  )
  Vue.component('ElForm', () =>
    import(/* webpackChunkName: 'element-ui-form' */ 'element-ui/lib/form')
  )
  Vue.component('ElFormItem', () =>
    import(
      /* webpackChunkName: 'element-ui-form-item' */ 'element-ui/lib/form-item'
    )
  )
  Vue.component('ElInput', () =>
    import(/* webpackChunkName: 'element-ui-input' */ 'element-ui/lib/input')
  )
  Vue.component('ElBadge', () =>
    import(/* webpackChunkName: 'element-ui-badge' */ 'element-ui/lib/badge')
  )
  Vue.component('ElRadio', () =>
    import(/* webpackChunkName: 'element-ui-radio' */ 'element-ui/lib/radio')
  )
}
