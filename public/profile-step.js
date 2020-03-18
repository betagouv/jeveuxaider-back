(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["profile-step"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=script&lang=js&":
/*!*******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=script&lang=js& ***!
  \*******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "ProfileStep",
  data: function data() {
    return {
      loading: false,
      activeName: "profil",
      form: {
        mobile: this.$store.getters.profile.mobile,
        phone: this.$store.getters.profile.phone
      },
      rules: {
        mobile: [{
          required: true,
          message: "Un numéro de téléphone est obligatoire",
          trigger: "blur"
        }, {
          pattern: /^[+|\s|\d]*$/,
          message: "Le format du numéro de téléphone est incorrect",
          trigger: "blur"
        }],
        phone: {
          pattern: /^[+|\s|\d]*$/,
          message: "Le format du numéro de téléphone est incorrect",
          trigger: "blur"
        }
      }
    };
  },
  computed: {
    avatar: function avatar() {
      return this.$store.getters.profile ? this.$store.getters.profile.avatar : null;
    },
    firstName: function firstName() {
      return this.$store.getters.profile ? this.$store.getters.profile.first_name : null;
    },
    lastName: function lastName() {
      return this.$store.getters.profile ? this.$store.getters.profile.last_name : null;
    }
  },
  methods: {
    // uploadAvatar(request) {
    //   this.$store.dispatch("user/updateProfileAvatar", {
    //     id: this.$store.getters.profile.id,
    //     avatar: request.file
    //   });
    // },
    // beforeAvatarUpload(file) {
    //   const isLt5M = file.size / 1024 / 1024 < 4;
    //   if (!isLt5M) {
    //     this.$message({
    //       message: "Votre image ne doit pas éxcéder une taille de 4MB",
    //       dangerouslyUseHTMLString: true,
    //       type: "error"
    //     });
    //   }
    //   return isLt5M;
    // },
    onSubmit: function onSubmit() {
      var _this = this;

      this.loading = true;
      this.$refs["profileForm"].validate(function (valid) {
        if (valid) {
          _this.$store.dispatch("user/updateProfile", _objectSpread({
            id: _this.$store.getters.profile.id
          }, _this.form)).then(function () {
            _this.loading = false;

            _this.$router.push("/register/step/structure");
          })["catch"](function () {
            _this.loading = false;
          });
        } else {
          _this.loading = false;
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=template&id=0976839b&":
/*!***********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=template&id=0976839b& ***!
  \***********************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _vm.$store.getters.profile
    ? _c(
        "div",
        { staticClass: "register-step" },
        [
          _c("portal", { attrs: { to: "register-steps-help" } }, [
            _c("p", [
              _vm._v("\n      Bienvenue " + _vm._s(_vm.firstName) + " ! "),
              _c("br"),
              _vm._v("Commencez par\n      "),
              _c("span", { staticClass: "font-bold" }, [
                _vm._v("compléter votre profil")
              ]),
              _vm._v(
                " pour finaliser la\n      création de votre compte de responsable de structure d’accueil SNU.\n    "
              )
            ])
          ]),
          _vm._v(" "),
          _c(
            "el-steps",
            {
              staticClass: "p-8 border-b border-b-2",
              attrs: { active: 1, "align-center": "" }
            },
            [
              _c("el-step", {
                attrs: {
                  title: "Profil",
                  description: "Je complète les informations de mon profil"
                }
              }),
              _vm._v(" "),
              _c("el-step", {
                attrs: {
                  title: "Structure",
                  description:
                    "J'enregistre ma structure en tant que responsable"
                }
              }),
              _vm._v(" "),
              _c("el-step", {
                attrs: {
                  title: "Adresse",
                  description: "J'enregistre le lieu de mon établissement"
                }
              })
            ],
            1
          ),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "max-w-lg p-12" },
            [
              _c("div", { staticClass: "font-bold text-2xl text-gray-800" }, [
                _vm._v("\n      Mon profil\n    ")
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "flex mt-6 mb-10" }, [
                _c(
                  "div",
                  { staticClass: "flex-1" },
                  [
                    _c(
                      "el-avatar",
                      {
                        staticClass: "bg-primary",
                        attrs: { size: 64, fit: "cover", src: _vm.avatar }
                      },
                      [
                        _vm.firstName
                          ? _c("span", [
                              _vm._v(
                                _vm._s(_vm.firstName[0]) +
                                  _vm._s(_vm.lastName[0])
                              )
                            ])
                          : _vm._e()
                      ]
                    )
                  ],
                  1
                )
              ]),
              _vm._v(" "),
              _c(
                "el-form",
                {
                  ref: "profileForm",
                  attrs: {
                    model: _vm.form,
                    "label-position": "top",
                    rules: _vm.rules
                  }
                },
                [
                  _c(
                    "el-form-item",
                    { attrs: { label: "Téléphone mobile", prop: "mobile" } },
                    [
                      _c("el-input", {
                        attrs: { placeholder: "Téléphone mobile" },
                        model: {
                          value: _vm.form.mobile,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "mobile", $$v)
                          },
                          expression: "form.mobile"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    { attrs: { label: "Téléphone fixe", prop: "phone" } },
                    [
                      _c("el-input", {
                        attrs: { placeholder: "Téléphone fixe" },
                        model: {
                          value: _vm.form.phone,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "phone", $$v)
                          },
                          expression: "form.phone"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "div",
                    { staticClass: "flex pt-2" },
                    [
                      _c(
                        "el-button",
                        {
                          attrs: { type: "primary", loading: _vm.loading },
                          on: { click: _vm.onSubmit }
                        },
                        [_vm._v("\n          Continuer\n        ")]
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/RegisterSteps/ProfileStep.vue":
/*!**********************************************************!*\
  !*** ./resources/js/views/RegisterSteps/ProfileStep.vue ***!
  \**********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ProfileStep_vue_vue_type_template_id_0976839b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ProfileStep.vue?vue&type=template&id=0976839b& */ "./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=template&id=0976839b&");
/* harmony import */ var _ProfileStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ProfileStep.vue?vue&type=script&lang=js& */ "./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ProfileStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ProfileStep_vue_vue_type_template_id_0976839b___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ProfileStep_vue_vue_type_template_id_0976839b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/RegisterSteps/ProfileStep.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=script&lang=js&":
/*!***********************************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=script&lang=js& ***!
  \***********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileStep.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=template&id=0976839b&":
/*!*****************************************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=template&id=0976839b& ***!
  \*****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileStep_vue_vue_type_template_id_0976839b___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ProfileStep.vue?vue&type=template&id=0976839b& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/ProfileStep.vue?vue&type=template&id=0976839b&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileStep_vue_vue_type_template_id_0976839b___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ProfileStep_vue_vue_type_template_id_0976839b___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);