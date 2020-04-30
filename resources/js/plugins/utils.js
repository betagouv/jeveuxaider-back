import store from "../store";
import Vue from "vue";
import slugify from "slugify";

Vue.filter("labelFromValue", function (key, taxonomy) {
  let element = store.getters.taxonomies[taxonomy].terms.find(el => {
    return el.value == key;
  });
  return element ? element.label : "";
});

Vue.filter("reseauFromValue", function (id) {
  let element = store.getters.reseaux.find(el => {
    return el.id == id;
  });
  return element ? element.name : "";
});

Vue.filter("departmentFromValue", function (key) {
  let department = store.getters.taxonomies.departments.terms.find(el => {
    return el.value == key;
  });
  return department ? department.label : "";
});

Vue.filter("fullDepartmentFromValue", function (key) {
  let department = store.getters.taxonomies.departments.terms.find(el => {
    return el.value == key;
  });
  return department ? `${department.value} - ${department.label}` : "";
});

Vue.filter("cleanCity", function (city) {
  return city.replace("Arrondissement", "");
});

Vue.filter("cleanDomaineAction", function (domaine) {
  switch (domaine) {
    case "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis":
      return "Aide alimentaire et d’urgence";
      break;
    case "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance":
      return "Garde exceptionnelle d’enfants";
      break;
    case "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)":
      return "Lien avec les personnes fragiles isolées";
      break;
    case "Je fais les courses de produits essentiels pour mes voisins les plus fragiles.":
      return "Solidarité de proximité";
      break;
    case "soutien_aux_personnes_agees_en_etablissement":
      return "Soutien aux personnes âgées en établissement";
      break;
    case "soutien_scolaire_a_distance":
      return "Soutien scolaire à distance";
      break;
    default:
      return domaine
  }
});

Vue.filter("domainIcon", function (domaine) {
  switch (domaine) {
    case "Je distribue des produits de première nécessité (aliments, hygiène, …) et des repas aux plus démunis":
      return "/images/groceries.svg";

    case "Je garde des enfants de soignants ou d’une structure de l’Aide Sociale à l’Enfance":
      return "/images/teddy-bear.svg";

    case "Je maintiens un lien (téléphone, visio, mail, …) avec des personnes fragiles isolées (âgées, malades, situation de handicap, de pauvreté, de précarité, etc.)":
      return "/images/phone-handle.svg";

    case "Je fais les courses de produits essentiels pour mes voisins les plus fragiles.":
      return "/images/basket.svg";

    case "soutien_aux_personnes_agees_en_etablissement":
      return "/images/ehpad.svg";

    case "soutien_scolaire_a_distance":
      return "/images/ecole.svg";

    default:
      return null
  }
});

Vue.filter("slugify", function (string) {
  return string ? slugify(string, {
    lower: true
  }) : string;
});

