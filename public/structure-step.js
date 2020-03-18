(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["structure-step"],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/forms/ItemDescription.vue?vue&type=script&lang=js&":
/*!********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/forms/ItemDescription.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************************************************************************************************/
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
/* harmony default export */ __webpack_exports__["default"] = ({
  name: "ItemDescription"
});

/***/ }),

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _api_structure__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @/api/structure */ "./resources/js/api/structure.js");
/* harmony import */ var _components_forms_ItemDescription__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @/components/forms/ItemDescription */ "./resources/js/components/forms/ItemDescription.vue");
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
  name: "StructureStep",
  components: {
    ItemDescription: _components_forms_ItemDescription__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  data: function data() {
    return {
      loading: false,
      structureId: null,
      form: {},
      logoPreview: null,
      rules: {
        name: {
          required: true,
          message: "Le nom de votre structure est requis",
          trigger: "blur"
        },
        statut_juridique: {
          required: true,
          message: "Veuillez renseigner la forme juridique de votre structure",
          trigger: "blur"
        },
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
    reseauxOptions: function reseauxOptions() {
      return this.$store.getters.reseaux;
    }
  },
  created: function created() {
    this.structureId = this.$store.getters.structure_as_responsable ? this.$store.getters.structure_as_responsable.id : null;
  },
  methods: {
    uploadLogo: function uploadLogo(request) {
      var _this = this;

      Object(_api_structure__WEBPACK_IMPORTED_MODULE_0__["updateStructureLogo"])(this.structureId, request.file).then(function () {
        _this.loading = false; // Get profile to get new role

        _this.$store.dispatch("user/get");

        _this.$router.push("/register/step/address");
      })["catch"](function () {
        _this.loading = false;
      });
    },
    beforeLogoUpload: function beforeLogoUpload(file) {
      var isLt5M = file.size / 1024 / 1024 < 5;

      if (!isLt5M) {
        this.$message({
          message: "Votre image ne doit pas éxcéder une taille de 4MB",
          type: "error"
        });
        this.loading = false;
        this.logoPreview = null;
      }

      return isLt5M;
    },
    onChangeLogo: function onChangeLogo(file) {
      var _this2 = this;

      var reader = new FileReader();
      reader.readAsDataURL(file.raw);

      reader.onload = function (e) {
        _this2.logoPreview = e.target.result;
      };
    },
    onSubmit: function onSubmit() {
      var _this3 = this;

      this.loading = true;
      this.$refs["structureForm"].validate(function (valid) {
        if (valid) {
          Object(_api_structure__WEBPACK_IMPORTED_MODULE_0__["addOrUpdateStructure"])(_this3.structureId, _this3.form).then(function (response) {
            _this3.structureId = response.data.id;

            if (_this3.$refs.logo.uploadFiles.length > 0) {
              _this3.$refs.logo.submit();
            } else {
              // Get profile to get new role
              _this3.$store.dispatch("user/get");

              _this3.$router.push("/register/step/address");

              _this3.loading = false;
            }
          })["catch"](function () {
            _this3.loading = false;
          });
        } else {
          _this3.loading = false;
        }
      });
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/forms/ItemDescription.vue?vue&type=template&id=2a399b2e&":
/*!************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/forms/ItemDescription.vue?vue&type=template&id=2a399b2e& ***!
  \************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "mt-2 mb-6 text-xs leading-snug text-gray-500 flex" },
    [
      _c("i", { staticClass: "el-icon-info text-primary mt-1 mr-2" }),
      _vm._v(" "),
      _c("div", { staticClass: "flex-1" }, [_vm._t("default")], 2)
    ]
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=template&id=416899e5&":
/*!*************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=template&id=416899e5& ***!
  \*************************************************************************************************************************************************************************************************************************/
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
    { staticClass: "register-step" },
    [
      _c("portal", { attrs: { to: "register-steps-help" } }, [
        _c("p", [
          _vm._v("\n      Dites-nous en plus sur votre structure !\n      "),
          _c("br"),
          _vm._v("Ces\n      "),
          _c("span", { staticClass: "font-bold" }, [
            _vm._v("informations générales")
          ]),
          _vm._v(
            " permettront au\n      service référent du SNU de mieux vous connaître.\n    "
          )
        ])
      ]),
      _vm._v(" "),
      _c(
        "el-steps",
        {
          staticClass: "p-8 border-b-2",
          attrs: { active: 2, "align-center": "" }
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
              description: "J'enregistre ma structure en tant que responsable"
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
            _vm._v("\n      Ma structure\n    ")
          ]),
          _vm._v(" "),
          _c(
            "div",
            {
              staticClass: "text-label pl-0 pb-2 mt-6",
              staticStyle: { "padding-left": "0" }
            },
            [_vm._v("\n      Logo de la structure\n    ")]
          ),
          _vm._v(" "),
          _c("div", { staticClass: "flex mb-10" }, [
            _c("div", { staticClass: "flex-1" }, [
              _vm.logoPreview
                ? _c("div", { staticClass: "h-32 w-32 flex items-center" }, [
                    _c("img", { attrs: { src: _vm.logoPreview, alt: "Logo" } })
                  ])
                : _c(
                    "div",
                    {
                      staticClass:
                        "default-picture h-32 w-32 font-bold flex items-center justify-center text-white text-2xl bg-primary"
                    },
                    [_vm._v("\n          LOGO\n        ")]
                  )
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "ml-8 mb-auto" },
              [
                _c(
                  "el-upload",
                  {
                    ref: "logo",
                    attrs: {
                      action: "",
                      "http-request": _vm.uploadLogo,
                      accept: "image/*",
                      "before-upload": _vm.beforeLogoUpload,
                      "auto-upload": false,
                      "on-change": _vm.onChangeLogo
                    }
                  },
                  [
                    _c("el-button", [_vm._v("Modifier")]),
                    _vm._v(" "),
                    _c(
                      "div",
                      {
                        staticClass: "el-upload__tip text-xs",
                        attrs: { slot: "tip" },
                        slot: "tip"
                      },
                      [
                        _vm._v(
                          "\n            Nous acceptons les fichiers au format PNG, JPG ou GIF, d'une\n            taille maximale de 5 Mo\n          "
                        )
                      ]
                    )
                  ],
                  1
                )
              ],
              1
            )
          ]),
          _vm._v(" "),
          _c(
            "el-form",
            {
              ref: "structureForm",
              attrs: {
                model: _vm.form,
                "label-position": "top",
                rules: _vm.rules
              }
            },
            [
              _c(
                "el-form-item",
                {
                  staticClass: "flex-1 mr-2",
                  attrs: { label: "Nom de votre structure", prop: "name" }
                },
                [
                  _c("el-input", {
                    attrs: { placeholder: "Nom de votre structure" },
                    model: {
                      value: _vm.form.name,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "name", $$v)
                      },
                      expression: "form.name"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "el-form-item",
                {
                  attrs: { label: "Statut juridique", prop: "statut_juridique" }
                },
                [
                  _c(
                    "el-select",
                    {
                      attrs: { placeholder: "Statut juridique" },
                      model: {
                        value: _vm.form.statut_juridique,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "statut_juridique", $$v)
                        },
                        expression: "form.statut_juridique"
                      }
                    },
                    _vm._l(
                      _vm.$store.getters.taxonomies.structure_legal_status
                        .terms,
                      function(item) {
                        return _c("el-option", {
                          key: item.value,
                          attrs: { label: item.label, value: item.value }
                        })
                      }
                    ),
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _vm.form.statut_juridique == "Association"
                ? _c(
                    "el-form-item",
                    {
                      attrs: {
                        label: "Disposez vous d'un agrément ?",
                        prop: "association_types"
                      }
                    },
                    [
                      _c(
                        "el-select",
                        {
                          attrs: {
                            placeholder: "Choix de l'agrément",
                            multiple: ""
                          },
                          model: {
                            value: _vm.form.association_types,
                            callback: function($$v) {
                              _vm.$set(_vm.form, "association_types", $$v)
                            },
                            expression: "form.association_types"
                          }
                        },
                        _vm._l(
                          _vm.$store.getters.taxonomies.association_types.terms,
                          function(item) {
                            return _c("el-option", {
                              key: item.value,
                              attrs: { label: item.label, value: item.value }
                            })
                          }
                        ),
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.form.statut_juridique == "Structure publique"
                ? _c(
                    "el-form-item",
                    { attrs: { prop: "structure_publique_type" } },
                    [
                      _c(
                        "el-select",
                        {
                          attrs: {
                            placeholder:
                              "Choisissez le type de votre structure publique"
                          },
                          model: {
                            value: _vm.form.structure_publique_type,
                            callback: function($$v) {
                              _vm.$set(_vm.form, "structure_publique_type", $$v)
                            },
                            expression: "form.structure_publique_type"
                          }
                        },
                        _vm._l(
                          _vm.$store.getters.taxonomies.structure_publique_types
                            .terms,
                          function(item) {
                            return _c("el-option", {
                              key: item.value,
                              attrs: { label: item.label, value: item.value }
                            })
                          }
                        ),
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.form.statut_juridique == "Structure publique" &&
              (_vm.form.structure_publique_type == "Service de l'Etat" ||
                _vm.form.structure_publique_type == "Etablissement public")
                ? _c(
                    "el-form-item",
                    { attrs: { prop: "structure_publique_etat_type" } },
                    [
                      _c(
                        "el-select",
                        {
                          attrs: { placeholder: "Choisissez une option" },
                          model: {
                            value: _vm.form.structure_publique_etat_type,
                            callback: function($$v) {
                              _vm.$set(
                                _vm.form,
                                "structure_publique_etat_type",
                                $$v
                              )
                            },
                            expression: "form.structure_publique_etat_type"
                          }
                        },
                        _vm._l(
                          _vm.$store.getters.taxonomies
                            .structure_publique_etat_types.terms,
                          function(item) {
                            return _c("el-option", {
                              key: item.value,
                              attrs: { label: item.label, value: item.value }
                            })
                          }
                        ),
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _vm.form.statut_juridique == "Structure privée"
                ? _c(
                    "el-form-item",
                    { attrs: { prop: "structure_privee_type" } },
                    [
                      _c(
                        "el-select",
                        {
                          attrs: {
                            placeholder:
                              "Choisissez le type de structure privée"
                          },
                          model: {
                            value: _vm.form.structure_privee_type,
                            callback: function($$v) {
                              _vm.$set(_vm.form, "structure_privee_type", $$v)
                            },
                            expression: "form.structure_privee_type"
                          }
                        },
                        _vm._l(
                          _vm.$store.getters.taxonomies.structure_privee_types
                            .terms,
                          function(item) {
                            return _c("el-option", {
                              key: item.value,
                              attrs: { label: item.label, value: item.value }
                            })
                          }
                        ),
                        1
                      )
                    ],
                    1
                  )
                : _vm._e(),
              _vm._v(" "),
              _c(
                "el-form-item",
                {
                  staticClass: "flex-1",
                  attrs: {
                    label: "Présentation synthétique de la structure",
                    prop: "description"
                  }
                },
                [
                  _c("el-input", {
                    attrs: {
                      type: "textarea",
                      autosize: { minRows: 2, maxRows: 6 },
                      placeholder: "Décrivez votre structure, en quelques mots"
                    },
                    model: {
                      value: _vm.form.description,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "description", $$v)
                      },
                      expression: "form.description"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "el-form-item",
                {
                  staticClass: "flex-1",
                  attrs: { label: "Numéro SIRET", prop: "siret" }
                },
                [
                  _c("item-description", [
                    _vm._v(
                      "\n          Si vous ne disposez pas d’un numéro SIRET, ne pas remplir cette case\n        "
                    )
                  ]),
                  _vm._v(" "),
                  _c("el-input", {
                    attrs: { placeholder: "Entrez votre numéro de SIRET" },
                    model: {
                      value: _vm.form.siret,
                      callback: function($$v) {
                        _vm.$set(_vm.form, "siret", $$v)
                      },
                      expression: "form.siret"
                    }
                  })
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "flex" },
                [
                  _c(
                    "el-form-item",
                    {
                      staticClass: "flex-1 mr-2",
                      attrs: { label: "Site Internet", prop: "website" }
                    },
                    [
                      _c("el-input", {
                        attrs: { placeholder: "http://" },
                        model: {
                          value: _vm.form.website,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "website", $$v)
                          },
                          expression: "form.website"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    {
                      staticClass: "flex-1 ml-2",
                      attrs: { label: "Facebook", prop: "facebok" }
                    },
                    [
                      _c("el-input", {
                        attrs: { placeholder: "http://" },
                        model: {
                          value: _vm.form.facebook,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "facebook", $$v)
                          },
                          expression: "form.facebook"
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "flex" },
                [
                  _c(
                    "el-form-item",
                    {
                      staticClass: "flex-1 mr-2",
                      attrs: { label: "Twitter", prop: "twitter" }
                    },
                    [
                      _c("el-input", {
                        attrs: { placeholder: "http://" },
                        model: {
                          value: _vm.form.twitter,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "twitter", $$v)
                          },
                          expression: "form.twitter"
                        }
                      })
                    ],
                    1
                  ),
                  _vm._v(" "),
                  _c(
                    "el-form-item",
                    {
                      staticClass: "flex-1 ml-2",
                      attrs: { label: "Instagram", prop: "instagram" }
                    },
                    [
                      _c("el-input", {
                        attrs: { placeholder: "http://" },
                        model: {
                          value: _vm.form.instagram,
                          callback: function($$v) {
                            _vm.$set(_vm.form, "instagram", $$v)
                          },
                          expression: "form.instagram"
                        }
                      })
                    ],
                    1
                  )
                ],
                1
              ),
              _vm._v(" "),
              _c(
                "div",
                { staticClass: "mb-6 mt-12 flex text-xl text-gray-800" },
                [_vm._v("\n        Réseau national\n      ")]
              ),
              _vm._v(" "),
              _c("item-description", [
                _vm._v(
                  "\n        Si votre structure est membre d'un réseau national (La Croix Rouge,\n        Armée du Salut...), renseignez son nom. Vous permettez ainsi au\n        superviseur de votre réseau de visualiser les missions et volontaires\n        rattachés à votre structure.\n      "
                )
              ]),
              _vm._v(" "),
              _c(
                "el-form-item",
                {
                  staticClass: "flex-1",
                  attrs: { label: "Réseau national", prop: "reseau" }
                },
                [
                  _c(
                    "el-select",
                    {
                      attrs: { clearable: "", placeholder: "Aucun" },
                      model: {
                        value: _vm.form.reseau_id,
                        callback: function($$v) {
                          _vm.$set(_vm.form, "reseau_id", $$v)
                        },
                        expression: "form.reseau_id"
                      }
                    },
                    _vm._l(_vm.reseauxOptions, function(item) {
                      return _c("el-option", {
                        key: item.id,
                        attrs: { label: item.name, value: item.id }
                      })
                    }),
                    1
                  )
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
                    [_vm._v("Continuer")]
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
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("api/structure", structure);
}
function updateStructure(id, structure) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("api/structure/".concat(id), structure);
}
function updateStructureLogo(id, logo) {
  var data = new FormData();
  data.append("logo", logo);
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("api/structure/".concat(id), data, {
    "Content-Type": "multipart/form-data"
  });
}
function addOrUpdateStructure(id, structure) {
  return id ? updateStructure(id, structure) : addStructure(structure);
}
function getStructure(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("api/structure/".concat(id));
}
function getStructureMembers(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("api/structure/".concat(id, "/members"));
}
function fetchStructureMissions(id, params) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].get("api/structure/".concat(id, "/missions"), {
    params: params
  });
}
function inviteStructureMember(id, member) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"].post("api/structure/".concat(id, "/members"), member);
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
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("api/structure/".concat(id));
}
function destroyStructure(id) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("api/structure/".concat(id, "/destroy"));
}
function deleteMember(structureId, memberId) {
  return _utils_request__WEBPACK_IMPORTED_MODULE_0__["default"]["delete"]("/api/structure/".concat(structureId, "/members/").concat(memberId));
}

/***/ }),

/***/ "./resources/js/components/forms/ItemDescription.vue":
/*!***********************************************************!*\
  !*** ./resources/js/components/forms/ItemDescription.vue ***!
  \***********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ItemDescription_vue_vue_type_template_id_2a399b2e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ItemDescription.vue?vue&type=template&id=2a399b2e& */ "./resources/js/components/forms/ItemDescription.vue?vue&type=template&id=2a399b2e&");
/* harmony import */ var _ItemDescription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ItemDescription.vue?vue&type=script&lang=js& */ "./resources/js/components/forms/ItemDescription.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _ItemDescription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ItemDescription_vue_vue_type_template_id_2a399b2e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ItemDescription_vue_vue_type_template_id_2a399b2e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/forms/ItemDescription.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/forms/ItemDescription.vue?vue&type=script&lang=js&":
/*!************************************************************************************!*\
  !*** ./resources/js/components/forms/ItemDescription.vue?vue&type=script&lang=js& ***!
  \************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemDescription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ItemDescription.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/forms/ItemDescription.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemDescription_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/forms/ItemDescription.vue?vue&type=template&id=2a399b2e&":
/*!******************************************************************************************!*\
  !*** ./resources/js/components/forms/ItemDescription.vue?vue&type=template&id=2a399b2e& ***!
  \******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemDescription_vue_vue_type_template_id_2a399b2e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ItemDescription.vue?vue&type=template&id=2a399b2e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/forms/ItemDescription.vue?vue&type=template&id=2a399b2e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemDescription_vue_vue_type_template_id_2a399b2e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ItemDescription_vue_vue_type_template_id_2a399b2e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/views/RegisterSteps/StructureStep.vue":
/*!************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/StructureStep.vue ***!
  \************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _StructureStep_vue_vue_type_template_id_416899e5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./StructureStep.vue?vue&type=template&id=416899e5& */ "./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=template&id=416899e5&");
/* harmony import */ var _StructureStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./StructureStep.vue?vue&type=script&lang=js& */ "./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _StructureStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _StructureStep_vue_vue_type_template_id_416899e5___WEBPACK_IMPORTED_MODULE_0__["render"],
  _StructureStep_vue_vue_type_template_id_416899e5___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/views/RegisterSteps/StructureStep.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=script&lang=js&":
/*!*************************************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=script&lang=js& ***!
  \*************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./StructureStep.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureStep_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=template&id=416899e5&":
/*!*******************************************************************************************!*\
  !*** ./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=template&id=416899e5& ***!
  \*******************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureStep_vue_vue_type_template_id_416899e5___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./StructureStep.vue?vue&type=template&id=416899e5& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/views/RegisterSteps/StructureStep.vue?vue&type=template&id=416899e5&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureStep_vue_vue_type_template_id_416899e5___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_StructureStep_vue_vue_type_template_id_416899e5___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);