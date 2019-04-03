import axios from "axios";
import * as actions from "../actions/bamConfigurationActions";
import {
  put,
  takeLatest,
  takeEvery,
  all,
  fork,
  take,
  call
} from "redux-saga/effects";

export function* setBaConfiguration() {
  yield takeEvery(actions.BA_CONFIGURATION_CHANGED, setConfiguration);
}

function* setConfiguration(action) {
  try {
    yield put({ type: actions.SET_BA_CONFIGURATION, data: action.data });
  } catch (err) {
    throw err;
  }
}