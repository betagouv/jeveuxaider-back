(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["structure-members"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/SNU/StructureMembers.vue?vue&type=script&lang=js&":
/*!**************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/SNU/StructureMembers.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _api_structure__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/api/structure */ "./resources/js/api/structure.js");
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
  name: "StructureMembers",
  props: {
    id: {
      type: Number,
      required: true
    }
  },
  data: function data() {
    return {
      structure: {},
      members: {}
    };
  },
  created: function created() {
    var _this = this;

    this.$store.commit("setLoading", true);
    Object(_api_structure__WEBPACK_IMPORTED_MODULE_0__["getStructure"])(this.id).then(function (response) {
      _this.$store.commit("setLoading", false);

      _this.structure = response.data;
    })["catch"](function (error) {
      _this.$store.commit("setLoading", false);

      _this.errors = error.response.data.errors;
    });
    Object(_api_structure__WEBPACK_IMPORTED_MODULE_0__["getStructureMembers"])(this.id).then(function (response) {
      _this.$store.commit("setLoading", false);

      _this.members = response.data;
    })["catch"](function (error) {
      _this.$store.commit("setLoading", false);

      _this.errors = error.response.data.errors;
    });
  },
  methods: {
    deleteConfirm: function deleteConfirm(member) {
      var _this2 = this;

      this.$confirm("Êtes vous sur de vouloir supprimer ce membre ?<br>Ce membre ne pourra plus être affecté à de nouvelles missions.", "Confirmation", {
        confirmButtonText: "Supprimer",
        confirmButtonClass: "el-button--danger",
        cancelButtonText: "Annuler",
        type: "warning",
        dangerouslyUseHTMLString: true
      }).then(function () {
        _this2.loading = true;
        Object(_api_structure__WEBPACK_IMPORTED_MODULE_0__["deleteMember"])(_this2.structure.id, member.id).then(function (response) {
          _this2.loading = false;

          _this2.$message({
            type: "success",
            message: "Le membre a bien été supprimé"
          });

          console.log(response.data);
          _this2.members = response.data;
        })["catch"](function (error) {
          _this2.loading = false;
          _this2.errors = error.response.data.errors;
        });
      })["catch"](function () {});
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/SNU/StructureMembers.vue?vue&type=template&id=9e336f4c&":
/*!******************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/SNU/StructureMembers.vue?vue&type=template&id=9e336f4c& ***!
  \******************************************************************************************************************************************************************************************************************/
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
  return _c(
    "div",
    { staticClass: "structure-members" },
    [
      _c(
        "div",
        { staticClass: "header px-12 flex" },
        [
          _c("div", { staticClass: "header-titles flex-1" }, [
            _c("div", { staticClass: "text-m text-gray-600 uppercase" }, [
              _vm._v("\n        " + _vm._s(_vm.structure.name) + "\n      ")
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "mb-12 font-bold text-2xl text-gray-800" },
              [_vm._v("\n        Gérer votre équipe\n      ")]
            )
          ]),
          _vm._v(" "),
          _c(
            "router-link",
            { attrs: { to: "/structure/" + _vm.id + "/members/add" } },
            [
              _c(
                "el-button",
                { attrs: { type: "primary", icon: "el-icon-plus" } },
                [_vm._v("\n        Inviter un membre\n      ")]
              )
            ],
            1
          )
        ],
        1
      ),
      _vm._v(" "),
      _c("el-divider"),
      _vm._v(" "),
      _c(
        "div",
        { staticClass: "px-12" },
        [
          _c(
            "div",
            { staticClass: "text-sm font-medium text-secondary mb-4" },
            [_vm._v("Membres")]
          ),
          _vm._v(" "),
          _vm._l(_vm.members, function(member) {
            return _c(
              "div",
              { key: member.id, staticClass: "member py-4 px-6" },
              [
                _c(
                  "div",
                  { staticClass: "flex" },
                  [
                    _c(
                      "el-avatar",
                      {
                        staticClass: "bg-primary w-10 rounded-full",
                        attrs: { src: "" + member.avatar }
                      },
                      [
                        _vm._v(
                          "\n          " +
                            _vm._s(member.first_name[0]) +
                            _vm._s(member.last_name[0]) +
                            "\n        "
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "flex flex-col ml-6",
                        staticStyle: { "min-width": "250px" }
                      },
                      [
                        _c("div", { staticClass: "text-gray-800" }, [
                          _vm._v(
                            "\n            " +
                              _vm._s(member.first_name) +
                              " " +
                              _vm._s(member.last_name) +
                              "\n          "
                          )
                        ]),
                        _vm._v(" "),
                        _c(
                          "div",
                          { staticClass: "uppercase text-xs text-secondary" },
                          [
                            _vm._v(
                              "\n            " +
                                _vm._s(member.pivot.role) +
                                "\n          "
                            )
                          ]
                        )
                      ]
                    ),
                    _vm._v(" "),
                    _vm.members.length > 1 &&
                    _vm.$store.getters.user.profile.id != member.id
                      ? _c(
                          "el-button",
                          {
                            staticClass: "ml-4 h-full m-auto is-plain",
                            attrs: {
                              type: "danger",
                              icon: "el-icon-delete",
                              size: "small"
                            },
                            on: {
                              click: function($event) {
                                return _vm.deleteConfirm(member)
                              }
                            }
                          },
                          [_vm._v("Supprimer")]
                        )
                      : _vm._e()
                  ],
                  1
                )
              ]
            )
          })
        ],
        2
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/api/structure.js":
/*!***************************************!*\
  !*** ./resources/js/api/structure.js ***!
  \***************************************/
/*! exports provided: addStructure, updateStructure, updateStructureLogo, addOrUpdateStructure, getStructure, getStructureMembers, fetchStructureMissions, inviteStructureMember, fetchStructures, exportStructures, deleteStructure, destroyStructure, deleteMember */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addStructure", function() { return addStructure; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateStructure", function() { return updateStructure; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "updateStructureLogo", function() { return updateStructureLogo; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "addOrUpdateStructure", function() { return addOrUpdateStructure; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getStructure", function() { return getStructure; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "getStructureMembers", function() { return getStructureMembers; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "fetchStructureMissions", function() { return fetchStructureMissions; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "inviteStructureMember", function() { return inviteStructureMember; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "fetchStructures", function() { return fetchStructures; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "exportStructures", function() { return exportStructures; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "deleteStructure", function() { return deleteStructure; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "destroyStructure", function() { return destroyStructure; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "deleteMember", function() { return deleteMember; });
/* harmony import */ var _utils_request__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../utils/request */ "./resources/js/utils/request.js");

function addStructure(structure) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/structure", structure);
}
function updateStructure(id, structure) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/structure/".concat(id), structure);
}
function updateStructureLogo(id, logo) {
  var data = new FormData();
  data.append("logo", logo);
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/structure/".concat(id), data, {
    "Content-Type": "multipart/form-data"
  });
}
function addOrUpdateStructure(id, structure) {
  return id ? updateStructure(id, structure) : addStructure(structure);
}
function getStructure(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/structure/".concat(id));
}
function getStructureMembers(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/structure/".concat(id, "/members"));
}
function fetchStructureMissions(id, params) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/structure/".concat(id, "/missions"), {
    params: params
  });
}
function inviteStructureMember(id, member) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("/api/structure/".concat(id, "/members"), member);
}
function fetchStructures(params) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/structures", {
    params: params
  });
}
function exportStructures(params) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("/api/structures/export", {
    params: params,
    responseType: "blob"
  });
}
function deleteStructure(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/structure/".concat(id));
}
function destroyStructure(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/structure/".concat(id, "/destroy"));
}
function deleteMember(structureId, memberId) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/structure/".concat(structureId, "/members/").concat(memberId));
}

/***/ }),

/***/ "./resources/js/views/SNU/StructureMembers.vue":
/*!*****************************************************!*\
  !*** ./resources/js/views/SNU/StructureMembers.vue ***!
  \*****************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _StructureMembers_vue_vue_type_template_id_9e336f4c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./StructureMembers.vue?vue&type=template&id=9e336f4c& */ "./resources/js/views/SNU/StructureMembers.vue?vue&type=template&id=9e336f4c&");
/* harmony import */ var _StructureMembers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./StructureMembers.vue?vue&type=script&lang=js& */ "./resources/js/views/SNU/StructureMembers.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _StructureMembers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _StructureMembers_vue_vue_type_template_id_9e336f4c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _StructureMembers_vue_vue_type_template_id_9e336f4c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/SNU/StructureMembers.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/SNU/StructureMembers.vue?vue&type=script&lang=js&":
/*!******************************************************************************!*\
  !*** ./resources/js/views/SNU/StructureMembers.vue?vue&type=script&lang=js& ***!
  \******************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureMembers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./StructureMembers.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/SNU/StructureMembers.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureMembers_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/SNU/StructureMembers.vue?vue&type=template&id=9e336f4c&":
/*!************************************************************************************!*\
  !*** ./resources/js/views/SNU/StructureMembers.vue?vue&type=template&id=9e336f4c& ***!
  \************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureMembers_vue_vue_type_template_id_9e336f4c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./StructureMembers.vue?vue&type=template&id=9e336f4c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/SNU/StructureMembers.vue?vue&type=template&id=9e336f4c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureMembers_vue_vue_type_template_id_9e336f4c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureMembers_vue_vue_type_template_id_9e336f4c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);