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
  Vue.component('ElInputNumber', () =>
    import(
      /* webpackChunkName: 'element-ui-input-number' */ 'element-ui/lib/input-number'
    )
  )
  Vue.component('ElBadge', () =>
    import(/* webpackChunkName: 'element-ui-badge' */ 'element-ui/lib/badge')
  )
  Vue.component('ElRadio', () =>
    import(/* webpackChunkName: 'element-ui-radio' */ 'element-ui/lib/radio')
  )
  Vue.component('ElCheckbox', () =>
    import(
      /* webpackChunkName: 'element-ui-checkbox' */ 'element-ui/lib/checkbox'
    )
  )
  Vue.component('ElContainer', () =>
    import(
      /* webpackChunkName: 'element-ui-container' */ 'element-ui/lib/container'
    )
  )
  Vue.component('ElTooltip', () =>
    import(
      /* webpackChunkName: 'element-ui-tooltip' */ 'element-ui/lib/tooltip'
    )
  )
  Vue.component('ElMenu', () =>
    import(/* webpackChunkName: 'element-ui-menu' */ 'element-ui/lib/menu')
  )
  Vue.component('ElMenuItem', () =>
    import(
      /* webpackChunkName: 'element-ui-menu-item' */ 'element-ui/lib/menu-item'
    )
  )
  Vue.component('ElButton', () =>
    import(/* webpackChunkName: 'element-ui-button' */ 'element-ui/lib/button')
  )
  Vue.component('ElAvatar', () =>
    import(/* webpackChunkName: 'element-ui-avatar' */ 'element-ui/lib/avatar')
  )
  Vue.component('ElAside', () =>
    import(/* webpackChunkName: 'element-ui-aside' */ 'element-ui/lib/aside')
  )
  Vue.component('ElCard', () =>
    import(/* webpackChunkName: 'element-ui-card' */ 'element-ui/lib/card')
  )
}
