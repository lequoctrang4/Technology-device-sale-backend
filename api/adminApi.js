import axios from "axios";

export const addProduct = async (token, formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "post",
      url: `localhost/product`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "application/json",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};

export const editProduct = async (token, formValue) => {
  try {
    // make axios post request
    const res = await axios({
      method: "post",
      url: `localhost/product`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "application/json",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};
export const deleteProduct = async (token, id) => {
  try {
    // make axios post request
    const res = await axios({
      method: "delete",
      url: `localhost/product?id=${id}`,
      data: formValue,
      headers: {
        Authorization: `Bearer ${token}`,
        "content-type": "application/json",
      },
    });
    return res.data;
  } catch (error) {
    return error.response.data;
  }
};