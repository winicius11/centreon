import axios from "../../axios";

export const FETCH_EXTERNAL_COMPONENTS_BEGIN = "FETCH_EXTERNAL_COMPONENTS_BEGIN";
export const FETCH_EXTERNAL_COMPONENTS_SUCCESS = "FETCH_EXTERNAL_COMPONENTS_SUCCESS";
export const FETCH_EXTERNAL_COMPONENTS_FAILURE = "FETCH_EXTERNAL_COMPONENTS_FAILURE";

export const fetchExternalComponents = () => {
  return async (dispatch) => {
    // Initiate loading state
    dispatch(fetchExternalComponentsBegin());

    try {
      // Call the API
      const { data } = await axios("internal.php?object=centreon_frontend_component&action=components").get();

     // Update payload in reducer on success
     dispatch(fetchExternalComponentsSuccess(data));
    } catch (err) {
     // Update error in reducer on failure
     dispatch(fetchExternalComponentsFailure(err));
    }
  };
}

const fetchExternalComponentsBegin = () => ({
  type: FETCH_EXTERNAL_COMPONENTS_BEGIN
});

const fetchExternalComponentsSuccess = (data) => ({
  type: FETCH_EXTERNAL_COMPONENTS_SUCCESS,
  data
});

const fetchExternalComponentsFailure = error => ({
  type: FETCH_EXTERNAL_COMPONENTS_FAILURE,
  error
});
