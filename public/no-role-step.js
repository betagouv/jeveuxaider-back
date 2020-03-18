(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["no-role-step"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
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
  name: "NoRoleStep"
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=template&id=d7db6da6&":
/*!**********************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=template&id=d7db6da6& ***!
  \**********************************************************************************************************************************************************************************************************************/
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
        { staticClass: "register-step h-full " },
        [
          _c("portal", { attrs: { to: "register-steps-help" } }, [
            _c("p", [
              _vm._v(
                "\n      Bienvenue " +
                  _vm._s(_vm.$store.getters.profile.first_name) +
                  " ! "
              ),
              _c("br"),
              _vm._v("Veuillez\n      selectionner "),
              _c("span", { staticClass: "font-bold" }, [
                _vm._v("votre type de profil")
              ]),
              _vm._v(
                " pour\n      finaliser la création de votre compte sur la plateforme SNU.\n    "
              )
            ])
          ]),
          _vm._v(" "),
          _c(
            "div",
            { staticClass: "flex flex-col justify-center items-center h-full" },
            [
              _c(
                "div",
                { staticClass: "font-bold text-4xl text-gray-800 mb-8" },
                [_vm._v("\n      Je choisis mon type de profil\n    ")]
              ),
              _vm._v(" "),
              _c("div", { staticClass: "flex flex-wrap" }, [
                _c(
                  "div",
                  {
                    staticClass:
                      "w-64 border p-8 m-8 rounded-lg flex flex-col text-center hover:border-primary",
                    staticStyle: { height: "270px" }
                  },
                  [
                    _c("div", { staticClass: "font-bold mb-4" }, [
                      _vm._v("Responsable")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "text-gray-600 mb-4 flex-1" }, [
                      _vm._v(
                        "\n          J'inscris ma structure en tant que responsable\n        "
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "router-link",
                      {
                        attrs: {
                          to: {
                            name: "ProfileStep"
                          }
                        }
                      },
                      [
                        _c("el-button", { attrs: { type: "primary" } }, [
                          _vm._v("Choisir")
                        ])
                      ],
                      1
                    )
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass:
                      "w-64 border p-8 m-8 rounded-lg flex flex-col text-center hover:border-primary",
                    staticStyle: { height: "270px" }
                  },
                  [
                    _c("div", { staticClass: "font-bold mb-4" }, [
                      _vm._v("Autre")
                    ]),
                    _vm._v(" "),
                    _c("div", { staticClass: "text-gray-600 mb-4 flex-1" }, [
                      _vm._v(
                        "\n          Je suis un tuteur, un référent départemental ou un superviseur\n        "
                      )
                    ]),
                    _vm._v(" "),
                    _c(
                      "router-link",
                      {
                        attrs: {
                          to: {
                            name: "OtherStep"
                          }
                        }
                      },
                      [
                        _c("el-button", { attrs: { type: "primary" } }, [
                          _vm._v("Choisir")
                        ])
                      ],
                      1
                    )
                  ],
                  1
                )
              ])
            ]
          )
        ],
        1
      )
    : _vm._e()
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/views/RegisterSteps/NoRoleStep.vue":
/*!*********************************************************!*\
  !*** ./resources/js/views/RegisterSteps/NoRoleStep.vue ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _NoRoleStep_vue_vue_type_template_id_d7db6da6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./NoRoleStep.vue?vue&type=template&id=d7db6da6& */ "./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=template&id=d7db6da6&");
/* harmony import */ var _NoRoleStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./NoRoleStep.vue?vue&type=script&lang=js& */ "./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _NoRoleStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _NoRoleStep_vue_vue_type_template_id_d7db6da6___WEBPACK_IMPORTED_MODULE_0__["render"],
  _NoRoleStep_vue_vue_type_template_id_d7db6da6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/RegisterSteps/NoRoleStep.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRoleStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./NoRoleStep.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRoleStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=template&id=d7db6da6&":
/*!****************************************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=template&id=d7db6da6& ***!
  \****************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRoleStep_vue_vue_type_template_id_d7db6da6___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./NoRoleStep.vue?vue&type=template&id=d7db6da6& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/NoRoleStep.vue?vue&type=template&id=d7db6da6&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRoleStep_vue_vue_type_template_id_d7db6da6___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_NoRoleStep_vue_vue_type_template_id_d7db6da6___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);