import axios from "axios";

export const signIn = async (formValue) => {
  try {
    // mobile:
    // password:
    const res = await axios({
      method: "post",
      url: `http://localhost/signIn`,
      data: formValue,
      headers: { "Content-Type": "text/plain" },
    });
    return res.data;
  } catch (error) {
    return error.response;
  }
};

export const signUp = async (formValue) => {
  try {
    //   {
    // "name": "Lê Quốc Trạng",
    // "mobile": "0399609015",
    // "email": "lequoctrang5@gmail.com",
    // "password": "Lequoctrang",
    // "confirmPassword": "Lequoctrang"
    // }
    const res = await axios({
      method: "post",
      url: `http://localhost/signUp`,
      data: formValue,
      headers: {
        "Content-Type": "text/plain",
      },
    });
    return res.data;
  } catch (error) {
    return error.response;
  }
};

export const getProfile = async (token) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/user/profile`,
      headers: { Authorization: `Bearer ${token}` },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const editProfile = async (token, formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "patch",
      url: `http://localhost/user/editProfile`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "text/plain",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const setAvatar = async (token, formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "patch",
      url: `http://localhost/user/setAvatar`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "Content-Type": "application/raw",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const getAvatar = async (token) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/user/getAvatar`,
      headers: { Authorization: `Bearer ${token}` },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const changePassword = async (token, formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "patch",
      url: `http://localhost/user/changePassword`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "text/plain",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const forgetPassword = async (formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "post",
      url: `http://localhost/user/forgetPassword`,
      data: formValue,
      headers: { "content-type": "text/plain" },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const getProductsByCate = async (category_id) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `localhost/products/?categoryId=${category_id}`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const getProductsByManufacturer = async (manuafacurer) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/products/?manufacturer=${manuafacurer}`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const getProductsById = async (id) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/products?id=${id}`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const getAllProduct = async () => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/products`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const getProductAttributes = async (id) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/attribute/products?id=${id}`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const getAllCategory = async () => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/categories`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const getReview = async (product_id) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/review/get?id=${product_id}`,
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const addReview = async (, formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "post",
      url: `http://localhost/review/post`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "text/plain",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const editReview = async (token) => {
  try {
    // make axios post request
    const res = await axios({
      method: "patch",
      url: `http://localhost/review/edit`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "text/plain",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const addOrder = async (token, formValue) => {
  try {
    // make axios post request
//     {
//     "sessionId": 123,
//     "token": 111,
//     "status": "notDone",
//     "tax": 1.2,
//     "subTotal": 120.3,
//     "voucherId": 111,
//     "shippingId": 101,
//     "note": "",
//     "products":
//     [
//         {
//             "productID": 1,
//             "discount": 0.4,
//             "quantity": 2,
//             "price": 5
//         }
//         ,{
//             "productID": 2,
//             "discount": 0.4,
//             "quantity": 2,
//             "price": 5
//         }
//     ]
// }
    const res = await axios({
      method: "post",
      url: `http://localhost/order`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "text/plain",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const getOrderByUser = async (token) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/orders`,
            headers: {
        Authorization: `Bearer ${token}`      }
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const getOrderById = async (token, id) => {
  try {
    // make axios post request
    const res = await axios({
      method: "get",
      url: `http://localhost/order?id=${id}`,
            headers: {
        Authorization: `Bearer ${token}`      }
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};