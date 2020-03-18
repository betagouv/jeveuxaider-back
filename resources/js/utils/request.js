import axios from "axios";
import store from "../store";
import { Message } from "element-ui";

// For sercurity reason
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

// create an axios instance
const request = axios.create({
  baseURL: process.env.MIX_API_BASE_URL
  // timeout: 5000 ( Ne convient pas pour l'export )
});

// request interceptor
request.interceptors.request.use(async config => {
  if (store.getters.contextRole) {
    config.headers["Context-Role"] = store.getters.contextRole;
  }
  if (!config.headers.Authorization) {
    if (store.state.auth.accessTokenImpersonate) {
      config.headers[
        "Authorization"
      ] = `Bearer ${store.state.auth.accessTokenImpersonate}`;
    } else if (store.state.auth.accessToken) {
      config.headers[
        "Authorization"
      ] = `Bearer ${store.state.auth.accessToken}`;
    }
  }

  return config;
});

// response interceptor
request.interceptors.response.use(
  response => response,
  error => {
    if (error.response && error.response.data) {
      if (
        error.response.data.message === "Unauthenticated." &&
        store.getters.isLogged
      ) {
        store.dispatch("auth/logout");
      } else if (error.response.data.errors) {
        Message({
          message: format_errors(error.response.data.errors),
          dangerouslyUseHTMLString: true,
          type: "error"
        });
      } else if (error.response.data.message) {
        Message({
          message: error.response.data.message,
          type: "error"
        });
      } else if (
        error.response.data == "Missing or wrong 'Context-Role' header"
      ) {
        store.dispatch("user/get");
      } else {
        Message({
          message: format_errors(error.response.data),
          type: "error"
        });
      }
    }
    return Promise.reject(error);
  }
);

function format_errors(errors) {
  var string = "";
  for (var errorField in errors) {
    string += errors[errorField][0] + "<br />";
  }
  return string;
}

export default request;
