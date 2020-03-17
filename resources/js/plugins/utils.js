import store from "../store";
import Vue from "vue";

Vue.filter("labelFromValue", function(key, taxonomy) {
  let element = store.getters.taxonomies[taxonomy].terms.find(el => {
    return el.value == key;
  });
  return element ? element.label : "";
});

Vue.filter("reseauFromValue", function(id) {
  let element = store.getters.reseaux.find(el => {
    return el.id == id;
  });
  return element ? element.name : "";
});

Vue.filter("departmentFromValue", function(key) {
  let department = store.getters.taxonomies.departments.terms.find(el => {
    return el.value == key;
  });
  return department ? department.label : "";
});

Vue.filter("fullDepartmentFromValue", function(key) {
  let department = store.getters.taxonomies.departments.terms.find(el => {
    return el.value == key;
  });
  return department ? `${department.value} - ${department.label}` : "";
});

Vue.filter("cleanCity", function(city) {
  return city.replace("Arrondissement", "");
});
