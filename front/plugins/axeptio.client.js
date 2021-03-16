export default () => {
  document.addEventListener('DOMContentLoaded', function () {
    const el = document.createElement('script')
    el.setAttribute('src', 'https://static.axept.io/sdk.js')
    el.setAttribute('type', 'text/javascript')
    el.setAttribute('async', true)
    el.setAttribute('data-id', '5fc77c519875a6702af31184')
    el.setAttribute('data-cookies-version', 'ga_fb')
    if (document.body !== null) {
      document.body.appendChild(el)
    }
  })
}
